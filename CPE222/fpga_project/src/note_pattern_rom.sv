module note_pattern_rom (
    input logic [4:0] addr,
    output logic [0:1] data
);
    always_comb begin
        case(addr) // 2'b11 not supported.
            5'd0:  data = 2'b10;
            5'd1:  data = 2'b01;
            5'd2:  data = 2'b01;
            5'd3:  data = 2'b01;
            5'd4:  data = 2'b10;
            5'd5:  data = 2'b01;
            5'd6:  data = 2'b10;
            5'd7:  data = 2'b10;
            5'd8:  data = 2'b10;
            5'd9:  data = 2'b01;
            5'd10: data = 2'b10;
            5'd11: data = 2'b01;
            5'd12: data = 2'b01;
            5'd13: data = 2'b10;
            5'd14: data = 2'b01;
            5'd15: data = 2'b10;
            5'd16: data = 2'b10;
            5'd17: data = 2'b10;
            5'd18: data = 2'b01;
            5'd19: data = 2'b10;
            5'd20: data = 2'b01;
            5'd21: data = 2'b10;
            5'd22: data = 2'b01;
            5'd23: data = 2'b01;
            5'd24: data = 2'b10;
            5'd25: data = 2'b01;
            5'd26: data = 2'b10;
            5'd27: data = 2'b10;
            5'd28: data = 2'b01;
            5'd29: data = 2'b01;
            5'd30: data = 2'b10;
            5'd31: data = 2'b10;
            default: data = 2'b00;
        endcase
    end
endmodule