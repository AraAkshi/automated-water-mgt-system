package com.serial;

import arduino.*;

public class Ser {

    public static void main(String[] args) {

        String input = "a060";
        Arduino arduino = new Arduino("COM6", 9600);
        arduino.openConnection();
        if (args.length > 0) {
            input = args[0];
        }
        arduino.serialWrite(input);
        System.out.println(input);
        arduino.closeConnection();

    }

}
