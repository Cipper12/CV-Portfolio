module decoder(
    input logic [2:0] Count,
    output logic a,
    output logic b,
    output logic c,
    output logic d,
    output logic e,
    output logic f,
    output logic g
);

    always_comb begin
        case(Count)
            3'b000: {a, b, c, d, e, f, g} = 7'b1111110; // 0
            3'b001: {a, b, c, d, e, f, g} = 7'b0110000; // 1
            3'b010: {a, b, c, d, e, f, g} = 7'b1101101; // 2
            3'b011: {a, b, c, d, e, f, g} = 7'b1111001; // 3
            3'b100: {a, b, c, d, e, f, g} = 7'b0110011; // 4
            3'b101: {a, b, c, d, e, f, g} = 7'b1011011; // 5
            default: {a, b, c, d, e, f, g} = 7'b0000000; // off
        endcase
    end

endmodule