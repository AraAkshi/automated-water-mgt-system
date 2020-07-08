package com.serial;

import java.util.Scanner;

import arduino.*;

public class BasicLEDExample {

	public static void main(String[] args) {
		
		Scanner ob = new Scanner(System.in);
		Arduino arduino = new Arduino("COM6", 9600); //enter the port name here, and ensure that Arduino is connected, otherwise exception will be thrown.
		arduino.openConnection();
		System.out.println("Enter 1 to switch LED on and 0  to switch LED off");
		//char input = ob.nextLine().charAt(0);
                String input=ob.next();
		while(input != "n"){
			arduino.serialWrite(input);
                        input=ob.next();
		}
		ob.close();
		arduino.closeConnection();
		

	}

}
