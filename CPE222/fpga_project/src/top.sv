module top (
    input logic clk,
    input logic reset_button,
    input logic btn1,
    input logic btn2,
    output [3:0] hdmi_tx_n,
    output [3:0] hdmi_tx_p,
    output logic a,
    output logic b,
    output logic c,
    output logic d,
    output logic e,
    output logic f,
    output logic g
);

    ////////////////////////// GENERATE PIXEL CLOCK /////////////////////////
    /////////////////////////////////////////////////////////////////////////

    // For 1280x720 60Hz, we need around 74.25 MHz for pixel clock.
    // https://projectf.io/posts/video-timings-vga-720p-1080p
    logic pix_clk;
    logic pix_clk_5x;
    logic pix_clk_lock;

    // This would produce 371.25 MHz clock for pix_clk_5x
    PLLVR #(
        .FCLKIN("27"),
        .FBDIV_SEL(54),
        .IDIV_SEL(3),
        .ODIV_SEL(2)
    ) INST_PLLVR (
        .CLKOUTP(),
        .CLKOUTD(),
        .CLKOUTD3(),
        .RESET(1'b0),
        .RESET_P(1'b0),
        .CLKFB(1'b0),
        .FBDSEL(6'b0),
        .IDSEL(6'b0),
        .ODSEL(6'b0),
        .PSDA(4'b0),
        .DUTYDA(4'b0),
        .FDLY(4'b0),
        .VREN(1'b1),
        .CLKIN(clk),
        .CLKOUT(pix_clk_5x),
        .LOCK(pix_clk_lock)
    );

    // Divide the 5x clock to get the pixel clock 74.25 MHz
    CLKDIV #(
        .DIV_MODE("5"),
        .GSREN("false")
    ) INST_CLKDIV (
        .CLKOUT(pix_clk),
        .HCLKIN(pix_clk_5x),
        .RESETN(pix_clk_lock),
        .CALIB(1'b1)
    );

    ///////////////////////////// RESET HANDLING ////////////////////////////
    /////////////////////////////////////////////////////////////////////////

    logic reset;
    always_comb begin
        reset = ~pix_clk_lock || ~reset_button;
    end

    //////////////////////// DISPLAY SIGNAL HANDLING ////////////////////////
    /////////////////////////////////////////////////////////////////////////

    logic signed [12:0] x, y;
    logic [2:0] hve_sync; // {de(2), vsync(1), hsync(0)}
    logic frame;

    display_signal #(
        .H_RESOLUTION(1280),
        .V_RESOLUTION(720),
        .H_FRONT_PORCH(110),
        .H_SYNC(40),
        .H_BACK_PORCH(220),
        .V_FRONT_PORCH(5),
        .V_SYNC(5),
        .V_BACK_PORCH(20),
        .H_SYNC_POLARITY(1),
        .V_SYNC_POLARITY(1)
    ) inst_display_signal (
        .i_pixel_clk(pix_clk),
        .i_reset(reset),
        .o_hvesync(hve_sync),
        .o_frame_start(frame),
        .o_x(x),
        .o_y(y)
    );

    //////////////////////// GENERATE RGB INFORMATION ///////////////////////
    /////////////////////////////////////////////////////////////////////////

    logic [23:0] rgb;
    rhythm_game inst_rhythm_game (
        .pix_clk(pix_clk),
        .reset(reset),
        .btn1(btn1),
        .btn2(btn2),
        .de(hve_sync[2]),
        .frame(frame),
        .x(x),
        .y(y),
        .red(rgb[7:0]),
        .green(rgb[15:8]),
        .blue(rgb[23:16]),
        .a(a),
        .b(b),
        .c(c),
        .d(d),
        .e(e),
        .f(f),
        .g(g)
    );

    ///////////////////////// GENERATE HDMI SIGNALS /////////////////////////
    /////////////////////////////////////////////////////////////////////////

    hdmi inst_hdmi (
        .reset(reset),
        .hdmi_clk(pix_clk),
        .hdmi_clk_5x(pix_clk_5x),
        .hve_sync(hve_sync),
        .rgb(rgb),
        .hdmi_tx_n(hdmi_tx_n),
        .hdmi_tx_p(hdmi_tx_p)
    );

endmodule