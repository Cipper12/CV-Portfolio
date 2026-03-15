module rhythm_game (
    input logic pix_clk,
    input logic reset,
    input logic btn1,
    input logic btn2,
    input logic de,
    input logic frame,
    input logic signed [12:0] x,
    input logic signed [12:0] y,
    output logic [7:0] red,
    output logic [7:0] green,
    output logic [7:0] blue,
    output logic a,
    output logic b,
    output logic c,
    output logic d,
    output logic e,
    output logic f,
    output logic g
);

    //////////////////////////// GAME PARAMETERS ////////////////////////////
    /////////////////////////////////////////////////////////////////////////

    // Screen sectioned into 3 sections where two lanes of 109 pixels are centered.
    localparam signed [12:0] H_RES = 1280;
    localparam signed [12:0] V_RES = 720;

    localparam LANE_COUNT = 2;
    localparam signed [12:0] LANE_WIDTH = 109;          // 33% of 1280
    localparam signed [12:0] TOTAL_LANE_WIDTH = LANE_COUNT * LANE_WIDTH;
    localparam signed [12:0] LANE_START_X = (H_RES - TOTAL_LANE_WIDTH)/2;
    
    localparam signed [12:0] INITIAL_SPEED = 4;         // pixels per frame
    localparam signed [12:0] MAX_SPEED = 10;            // pixels per frame
    localparam [7:0] NOTES_PER_SPEED_INCREASE = 15;
    localparam signed [12:0] NOTE_HEIGHT = 20;
    localparam [5:0] MAX_NOTES = 32;                    // this amount heavily impacts resource usage

    // Hit detection zones (distance from bottom)
    localparam signed [12:0] HIT_ZONE = 30;             // ±x pixels from target
    localparam signed [12:0] HIT_Y = V_RES - 13'd100;   // Target line position
    
    // Timing parameters for 138 BPM. 
    // 60 fps / (138 bpm/60) ≈ 26 frames per beat. 
    // - 138/60 to find beats per second
    // - then the units would cancel out to frames per beat.
    // We also get around 2.3 beats per second.
    localparam FRAMES_PER_BEAT = 26;

    /////////////////////////// BUTTON DEBOUNCING ///////////////////////////
    /////////////////////////////////////////////////////////////////////////

    logic sig_btn1, sig_btn2;
    
    debounce inst_debounce_1 (.clk(pix_clk), .pb_1(btn1), .pb_out(sig_btn1));
    debounce inst_debounce_2 (.clk(pix_clk), .pb_1(btn2), .pb_out(sig_btn2));

    ////////////////////////////// NOTE LOGIC ///////////////////////////////
    /////////////////////////////////////////////////////////////////////////

    typedef struct packed {
        logic active;               // 1 if note exists
        logic signed [12:0] y_pos;  // vertical position
        logic lane;
    } note_t;

    note_t notes [MAX_NOTES-1:0];
    logic [4:0] pattern_addr;
    logic [0:1] pattern_data;

    note_pattern_rom inst_note_pattern_rom (
        .addr(pattern_addr),
        .data(pattern_data)
    );

    logic pressed_lane;
    always_comb begin
        if (sig_btn1 && !sig_btn2) begin
            pressed_lane = 1'b0;
        end else if (!sig_btn1 && sig_btn2) begin
            pressed_lane = 1'b1;
        end else begin
            pressed_lane = 1'bx;
        end 
    end

    //////////////////////////// STATE HANDLING /////////////////////////////
    /////////////////////////////////////////////////////////////////////////

    typedef enum {
        NEW_GAME,   // Initial state, waiting for btn1
        PLAYING,    // Game is active
        GAME_OVER   // Game finished, waiting for btn1
    } game_state_t;
    
    game_state_t state, next_state;
    always_ff @(posedge pix_clk) begin
        if (reset) begin
            state <= NEW_GAME;
        end else begin
            state <= next_state;
        end
    end

    logic [2:0] misses;
    always_comb begin
        case (state)
            NEW_GAME: next_state = (sig_btn1 || sig_btn2) ? PLAYING : NEW_GAME;
            PLAYING: next_state = (misses == 3'd5) ? GAME_OVER : PLAYING;
            GAME_OVER: next_state = (sig_btn1 || sig_btn2) ? NEW_GAME : GAME_OVER;
            default: next_state = NEW_GAME;
        endcase
    end

    /////////////////////////// MAIN GAME LOGIC /////////////////////////////
    /////////////////////////////////////////////////////////////////////////

    // Process hits all the time (update at next frame)
    // Also increment the notes_hit_counter if a note is hit, for controlling speed
    logic signed [12:0] distance;
    logic hit_detected;
    logic [5:0] hit_note_index;
    logic hit_pending;
    logic [7:0] notes_hit_counter;
    always_ff @(posedge pix_clk) begin
        if (reset || state != PLAYING) begin
            hit_detected <= 0;
            hit_note_index <= 0;
            hit_pending <= 0;
            notes_hit_counter <= 0;
        end else begin
            if ((sig_btn1 || sig_btn2) && !hit_pending) begin
                hit_detected <= 0;
                for (int i = 0; i < MAX_NOTES; i++) begin
                    if (notes[i].active && notes[i].lane == pressed_lane) begin
                        distance = HIT_Y - notes[i].y_pos;
                        if (distance < 0) distance = -distance;

                        if (distance <= HIT_ZONE) begin
                            hit_detected <= 1;
                            hit_note_index <= i;
                            hit_pending <= 1;
                            notes_hit_counter <= notes_hit_counter + 8'd1;
                            if (notes_hit_counter + 8'd1 > NOTES_PER_SPEED_INCREASE) begin
                                notes_hit_counter <= 0;
                            end
                            break;
                        end
                    end
                end
            end else if (!sig_btn1 && !sig_btn2) begin
                hit_pending <= 0;
            end
        end
    end

    // Speed control
    logic signed [12:0] current_speed;
    logic speed_increase_pending;
    always_ff @(posedge pix_clk) begin
        if (reset || state != PLAYING) begin
            current_speed <= INITIAL_SPEED;
            speed_increase_pending <= 0;
        end else if (notes_hit_counter >= NOTES_PER_SPEED_INCREASE && !speed_increase_pending) begin
            if (current_speed < MAX_SPEED) begin
                current_speed <= current_speed + 13'd1;
                speed_increase_pending <= 1;
            end
        end else if (notes_hit_counter < NOTES_PER_SPEED_INCREASE) begin
            speed_increase_pending <= 0;
        end
    end

    // Bundle of main logic
    logic [6:0] spawn_counter;
    always_ff @(posedge pix_clk) begin
        // Reset handling
        if (reset || state == NEW_GAME) begin
            pattern_addr <= 0;
            spawn_counter <= 0;
            misses <= 0;
            for (int i = 0; i < MAX_NOTES; i++) begin
                notes[i].active <= 0;
                notes[i].y_pos <= 0;
                notes[i].lane <= 0;
            end
        end // Note spawning logic
        else if (frame && state == PLAYING) begin 
            // Spawn new notes every beat (every FRAMES_PER_BEAT frames)
            // 'frame' is high for one pixel clock at first pixel of blanking
            // every frame will increment the spawn_counter
            if (spawn_counter >= FRAMES_PER_BEAT) begin
                spawn_counter <= 0;
                
                // Spawn notes based on pattern
                if (pattern_data != 2'b00) begin
                    for (int i = 0; i < MAX_NOTES; i++) begin
                        // If slot is empty, spawn note
                        if (!notes[i].active) begin
                            // Spawn in right lane if pattern_data[0] is high
                            if (pattern_data[0]) begin
                                notes[i].active <= 1;
                                notes[i].y_pos <= -NOTE_HEIGHT;
                                notes[i].lane <= 0;
                                break;
                            end
                            else begin
                                notes[i].active <= 1;
                                notes[i].y_pos <= -NOTE_HEIGHT;
                                notes[i].lane <= 1;
                                break;
                            end
                        end
                    end
                end

                // Increment pattern address and loop back to 0
                // This runs every FRAMES_PER_BEAT frames
                pattern_addr <= (pattern_addr == 5'd31) ? 0 : {pattern_addr + 5'd1};
            end else begin
                spawn_counter <= spawn_counter + 7'd1;
            end

            // Move and check notes for misses
            for (int i = 0; i < MAX_NOTES; i++) begin
                if (notes[i].active) begin
                    // If note is still on screen, move it
                    if ((notes[i].y_pos + current_speed + NOTE_HEIGHT) < V_RES) begin
                        notes[i].y_pos <= notes[i].y_pos + current_speed;
                        
                        if (notes[i].y_pos > HIT_Y + HIT_ZONE) begin
                            notes[i].active <= 0;
                            notes[i].y_pos <= 0;
                            notes[i].lane <= 0;
                            misses <= misses + 3'd1;
                        end
                    end // If note is off screen, remove it
                    else begin
                        notes[i].active <= 0;
                        notes[i].y_pos <= 0;
                        notes[i].lane <= 0;
                        misses <= misses + 3'd1;
                    end
                    
                end
            end

            // Check for hits
            if (hit_detected) begin
                notes[hit_note_index].active <= 0;
                notes[hit_note_index].y_pos <= 0;
                notes[hit_note_index].lane <= 0;
            end
        end
    end

    ///////////////////////////// DISPLAY LOGIC //////////////////////////////
    /////////////////////////////////////////////////////////////////////////

    // 7-segment display
    decoder inst_decoder (
        .Count(misses),
        .a(a),
        .b(b),
        .c(c),
        .d(d),
        .e(e),
        .f(f),
        .g(g)
    );
    
    // HDMI display
    logic note_visible;
    logic top_hit_line, bottom_hit_line;
    logic signed [12:0] note_x;
    
    always_comb begin
        // Check if current pixel is a note
        note_x = 0;
        note_visible = 0;
        if (state == PLAYING) begin
            for (int i = 0; i < MAX_NOTES; i++) begin
                if (notes[i].active) begin
                    note_x = LANE_START_X + (notes[i].lane * LANE_WIDTH);
                    if (x >= note_x && x < note_x + LANE_WIDTH &&
                        y >= notes[i].y_pos && y < notes[i].y_pos + NOTE_HEIGHT) begin
                        note_visible = 1;
                    end
                end
            end
        end

        // Hit line
        top_hit_line = (y == HIT_Y && x >= LANE_START_X && x < LANE_START_X + (LANE_COUNT * LANE_WIDTH));
        bottom_hit_line = (y == HIT_Y + NOTE_HEIGHT && x >= LANE_START_X && x < LANE_START_X + (LANE_COUNT * LANE_WIDTH));

        // Color output
        if (!de) begin
            {red, green, blue} = 24'h000000;
        end else if (note_visible) begin
            {red, green, blue} = 24'hFFFFFF;
        end else if (top_hit_line || bottom_hit_line) begin
            {red, green, blue} = 24'h525252;
        end else begin
            {red, green, blue} = 24'h000000;
        end
    end

endmodule