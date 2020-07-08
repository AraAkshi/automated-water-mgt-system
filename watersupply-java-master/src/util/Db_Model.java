/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package util;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.JOptionPane;

/**
 *
 * @author HP
 */
public class Db_Model {

    void setAutomaticShedule(String time, String cStart, String cStop, int delay) {
        try {
            //System.out.println("8:".matches("[\\:]"));

            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            Date dt = sdf.parse(time);
            System.out.println(dt.toString());

            Timer t = new Timer("auto");
            TimerTask ts = new TimerTask() {
                @Override
                public void run() {
                    JOptionPane.showMessageDialog(null, "MotorStopped");
                    System.out.println("MotorStopped");
                }
            };
            int i = 0;
            TimerTask tt = new TimerTask() {
                @Override
                public void run() {
                    JOptionPane.showMessageDialog(null, "Hello buddy\nMotorStarted");
                    System.out.println("MotorStarted");
                    //t.schedule(ts,5000);

                }
            };

            if (dt.after(new Date())) {             //c.compareTo(new GregorianCalendar())>0
//                t.schedule(tt, dt);
                t.schedule(tt, dt, (delay * 1000));
                t.schedule(ts, dt, ((delay * 2) * 1000));
                System.out.println("Time Sheduled.." + dt);
            } else {
                System.out.println("Time passed..");
            }

        } catch (Exception e) {
            e.printStackTrace();
        }

    }

    public static void main(String[] args) throws ParseException {
//        new MainUI().saveConfig();
//        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
//        Date dt = sdf.parse("2018-10-15 14:58:00");
//        System.out.println(dt.toString());
        String time = "14:58:50";
        if (time.matches("([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]")) {
            System.out.println("MatchFound");
        } else {
            System.out.println("MatchNotFound");
        }

        new Db_Model().setAutomaticShedule("2018-01-16 02:53:00", "a120", "a000", 5);
        System.out.println("done");
    }
}
