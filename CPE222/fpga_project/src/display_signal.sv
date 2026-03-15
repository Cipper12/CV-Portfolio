// display_signal module converts a pixel clock into a hsync+vsync+disp_enable+x+y structure.
module display_signal #(
    parameter H_RESOLUTION    = 640,
    parameter V_RESOLUTION    = 480,
    parameter H_FRONT_PORCH   = 16,
    parameter H_SYNC          = 96,
    parameter H_BACK_PORCH    = 48,
    parameter V_FRONT_PORCH   = 10,
    parameter V_SYNC          = 2,
    parameter V_BACK_PORCH    = 33,
    parameter H_SYNC_POLARITY = 0,   // 0: neg, 1: pos
    parameter V_SYNC_POLARITY = 0    // 0: neg, 1: pos
) (
    input  wire                   i_pixel_clk,
    input  wire                   i_reset,               // reset is active high
    output wire [2:0]             o_hvesync,             // { display_enable, vsync, hsync} . hsync is active at desired H_SYNC_POLARITY and vsync is active at desired V_SYNC_POLARITY, display_enable is active high, low in blanking
    output wire                   o_frame_start,         // counts high for one pixel clock of a frame (inside blanking)
    output reg  signed [12:0]     o_x,                   // screen x coordinate (negative in blanking, nonneg in visible picture area)
    output reg  signed [12:0]     o_y                    // screen y coordinate (negative in blanking, nonneg in visible picture area)
);

    // A horizontal scanline consists of sequence of regions: front porch -> sync -> back porch -> display visible
    localparam signed H_START       = -H_BACK_PORCH - H_SYNC - H_FRONT_PORCH;
    localparam signed HSYNC_START   = -H_BACK_PORCH - H_SYNC;
    localparam signed HSYNC_END     = -H_BACK_PORCH;
    localparam signed HACTIVE_START = 0;
    localparam signed HACTIVE_END   = H_RESOLUTION - 1;

    // Vertical image frame has the same structure, but counts scanlines instead of pixel clocks.
    localparam signed V_START       = -V_BACK_PORCH - V_SYNC - V_FRONT_PORCH;
    localparam signed VSYNC_START   = -V_BACK_PORCH - V_SYNC;
    localparam signed VSYNC_END     = -V_BACK_PORCH;
    localparam signed VACTIVE_START = 0;
    localparam signed VACTIVE_END   = V_RESOLUTION - 1;

    // generate display_enable, vsync and hsync signals with desired polarity
    assign o_hvesync = {
        o_x >= 0 && o_y >= 0,                                                   // display enable is high when in visible picture area
        1'(V_SYNC_POLARITY) ^ (o_y >= VSYNC_START && o_y < VSYNC_END),           // vsync. ^ is XOR, which outputs 1 when the two inputs are different. "1'" specifies size for rigorous comparison.
        1'(H_SYNC_POLARITY) ^ (o_x >= HSYNC_START && o_x < HSYNC_END)            // hsync
    };

    // counts high for one pixel clock at the beginning of a new frame (inside hblank and vblank)
    assign o_frame_start = (o_y == V_START && o_x == H_START);

    // count frame x & y pixel coordinates. Values < 0 denote pixels inside blanking/vsync area,
    // values >= 0 denote visible image. (x,y)==(0,0) is top left.
    always @(posedge i_pixel_clk) begin
        if (i_reset) begin
            o_x <= H_START;
            o_y <= V_START;
        end else begin
            if (o_x == HACTIVE_END) begin
                o_x <= H_START;
                o_y <= o_y == VACTIVE_END ? 13'(V_START) : o_y + 1'b1;
            end else begin
                o_x <= o_x + 1'b1;
            end
        end
    end
endmodule
