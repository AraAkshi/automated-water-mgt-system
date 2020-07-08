/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package util;

import arduino.Arduino;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import java.awt.Color;
import java.awt.Frame;
import java.io.BufferedWriter;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.URL;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.Iterator;
import java.util.Properties;
import java.util.Timer;
import java.util.TimerTask;
import javax.swing.JOptionPane;

/**
 *
 * @author Muznious
 */
public class MainUI extends javax.swing.JFrame {

    /**
     * Creates new form HomeUI
     */
    private static Arduino arduino = null;

    public MainUI() {
        initComponents();
        //setComponentStatesToFrame();
    }

    public MainUI(Arduino arduino) {
        initComponents();
        getConfig();
        this.arduino = arduino;
        System.out.println("PORT : " + this.arduino.getPortDescription());
        setDeviceOnline(this.arduino.getPortDescription(), "on");
        readSensorValue();
        readCommands();
        setAutomaticSheduleForAll();
        logSensorReadings(1);
    }
    int count = 0;
    Thread t1, t2, t3;

    private String sensorValue = "Processing...";
    private String sval;
    private static String serverPath;
    private static String activationKey;
    private static String logFileName = "SensorReadings.txt";
    private static int logDuration = 10;
    private Timer t;
    private int mode = 0;

    public void readSensorValue() {
        try {
            t1 = new Thread(new Runnable() {
                @Override
                public void run() {
                    try {

                        while (true) {
                            String x = arduino.serialRead(0);
//                            System.out.println("x: "+x);
                            if (x.startsWith("svl::")) {
                                setSensorData(x);
                            }
                            Thread.sleep(1000);
                        }
                    } catch (Exception ex) {
                        JOptionPane.showMessageDialog(rootPane, ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
                    }
                }
            }, "ThreadSX");
            t1.start();
            System.out.println("Thread Exit " + t1.getState() + " :: " + t1.getName());

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    void setSensorData(String svalue) {
        this.sensorValue = svalue;
        //svl::mois_71::rain_0::temp_-999.00::humi_-999.00
        String vparts[] = svalue.split("::");
        for (String svalx : vparts) {

            String sarr[] = svalx.split("_");
            if (sarr[0].trim().equals("svl")) {
                continue;
            }
            String pfx = sarr[0].trim();
            String val = sarr[1].trim();

            switch (pfx) {
                case "mois":
                    setMoistureData(val);
                    break;
                case "rain":
                    setRainData(val);
                    break;
                case "temp":
                    setTemperatureData(val);
                    break;
                case "humi":
                    setHumidityData(val);
                    break;
            }
        }
        storeSensorJSONValueToServer(svalue);
    }

    void setMoistureData(String val) {

        lblMoisture.setText("Moisture : " + val + "%");
        int svl = Integer.parseInt(val);

        if (svl == 0) {
            lblAlert.setText("Critical");
            lblAlert.setForeground(Color.red);
        } else if (svl > 0 && svl < 30) {
            lblAlert.setText("Low");
            lblAlert.setForeground(Color.decode("#ed3b3b"));
        } else if (svl >= 30 && svl < 70) {
            lblAlert.setText("Average");
            lblAlert.setForeground(Color.decode("#7084ed"));
        } else if (svl >= 70 && svl <= 100) {
            lblAlert.setText("Fine");
            lblAlert.setForeground(Color.decode("#028102"));
        }

        if (svl == 100) {
            lblAlert2.setText("<html>Crop Lands are reached 100% moisture<br>Turn off motors</html>");
            lblAlert2.setForeground(Color.decode("#007700"));
            if (autoMoisture == 1 && mode == 0) {
                setAllMotorsOff();
                mode = 1;
            }
        } else {
            lblAlert2.setText("");
            mode = 0;
        }
    }

    void setTemperatureData(String val) {
        lblTemperature.setText("Temperature : " + val + "C");
    }

    void setHumidityData(String val) {
        lblHumidity.setText("Humidity : " + val + "%");
    }

    void setRainData(String val) {
        int svl = Integer.parseInt(val);
        String txt = "";
        switch (svl) {
            case 0:
                txt = "Raining";
                break;
            case 1:
                txt = "Rain Warning";
                break;
            case 2:
                txt = "Not Raining";
                break;
        }

        lblRain.setText("Rain : " + txt);
    }

    void logSensorReadings(int duration) {
        try {
            t3 = new Thread(new Runnable() {
                @Override
                public void run() {
                    try {
                        while (true) {
                            logToFile();
                            Thread.sleep(duration * 60 * 1000);
                        }
                    } catch (Exception e) {

                    }
                }
            });
            t3.start();
        } catch (Exception e) {
        }
    }

    void logToFile() throws Exception {
        DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        String txt = df.format(new Date()) + "\t" + this.sensorValue;

        BufferedWriter writer = new BufferedWriter(new FileWriter(logFileName, true));
        writer.newLine();   //Add new line
        writer.write(txt);
        writer.close();
    }

    void readCommands() {

        try {
            t2 = new Thread(new Runnable() {
                @Override
                public void run() {
                    try {
                        while (true) {
                            try {
                                String pth = serverPath + "/data/commands.json";
                                URL u = new URL(pth);
                                InputStream in = u.openStream();
                                JsonParser jp = new JsonParser();
                                InputStreamReader insr = new InputStreamReader(in);
                                JsonElement je = jp.parse(insr);
                                JsonObject ob = je.getAsJsonObject();
                                JsonArray s = ob.get("commands").getAsJsonArray();
                                //System.out.println(je);

                                in.close();
                                insr.close();

                                Iterator<JsonElement> it = s.iterator();
                                while (it.hasNext()) {
                                    String comm = it.next().getAsString();
                                    //System.out.println(comm);
                                    if (comm.startsWith("mc_")) {
                                        String c[] = comm.split("mc_");
                                        System.out.println("MotorControl " + c[1]);
                                        //Write to serial
                                        serialWriteToDevice(c[1]);
                                        setCommandsEmpty();
                                    } else if (comm.startsWith("db_")) {
                                        String c[] = comm.split("db_");
                                        System.out.println("DatabaseControl " + c[1]);
                                        if (c[1].equals("update")) {
                                            storeDatabaseToFile();  //db_update
                                            System.out.println("DatabaseUpdated");
                                        } else {
                                            System.out.println("UnknownDBCommand");
                                        }
                                        setCommandsEmpty();
                                    } else if (comm.startsWith("uc_")) {
                                        String c[] = comm.split("uc_");
                                        System.out.println("UserControl " + c[1]);
                                        if (c[1].equals("autoSchedule")) {
                                            setAutomaticSheduleForAll();
                                            System.out.println("SystemSheduledAutomatically");
                                        } else if (c[1].equals("removeSchedule")) {
                                            removeAllSchedules();
                                            setAllMotorsOff();
                                        } else {
                                            System.out.println("UnknownUserCommand");
                                        }
                                        setCommandsEmpty();
                                    }
                                }
                            } catch (Exception e) {
                                e.printStackTrace();
                            }
                            Thread.sleep(800);
                        }
                    } catch (Exception ex) {
                        JOptionPane.showMessageDialog(rootPane, ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
                    }
                }
            }, "ThreadCommandReader");
            t2.start();
            System.out.println("Thread Exit " + t2.getState() + " :: " + t2.getName());

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    void setDeviceOnline(String key, String val) {
        try {
            String jdata = key + "=" + val;
            URL url = new URL(serverPath + "/index.php/motor/setDeviceOnline/" + jdata);
            url.openStream();
            System.out.println("Done " + jdata + " " + url);
        } catch (java.net.ConnectException ex) {
            JOptionPane.showMessageDialog(rootPane, "No connection\nPlease connect to the server", "Connection Error", JOptionPane.ERROR_MESSAGE);
            System.exit(0);
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(rootPane, ex, "Error", JOptionPane.ERROR_MESSAGE);
            System.exit(0);
        }
    }

    void setCommandsEmpty() {
        try {
            String pthx = serverPath + "/index.php/motor/storeCommands";
            URL ux = new URL(pthx);
            InputStream inx = ux.openStream();
            inx.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        jPanel1 = new javax.swing.JPanel();
        lblClose = new javax.swing.JLabel();
        lblTitle = new javax.swing.JLabel();
        lblMoisture = new javax.swing.JLabel();
        btnAllOff = new javax.swing.JButton();
        jButton3 = new javax.swing.JButton();
        jButton4 = new javax.swing.JButton();
        jButton5 = new javax.swing.JButton();
        jButton6 = new javax.swing.JButton();
        jLabel7 = new javax.swing.JLabel();
        lblAlert = new javax.swing.JLabel();
        lblAlert2 = new javax.swing.JLabel();
        lblAutoMoisture = new javax.swing.JLabel();
        jLabel2 = new javax.swing.JLabel();
        lblTemperature = new javax.swing.JLabel();
        lblHumidity = new javax.swing.JLabel();
        lblRain = new javax.swing.JLabel();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        setTitle("ExhiWebApp");
        setName("ExhiWebApp"); // NOI18N
        setUndecorated(true);
        setResizable(false);

        jPanel1.setBackground(new java.awt.Color(255, 255, 255));
        jPanel1.setBorder(new javax.swing.border.LineBorder(new java.awt.Color(0, 0, 0), 1, true));
        jPanel1.setCursor(new java.awt.Cursor(java.awt.Cursor.DEFAULT_CURSOR));
        jPanel1.setLayout(new org.netbeans.lib.awtextra.AbsoluteLayout());

        lblClose.setFont(new java.awt.Font("Tempus Sans ITC", 1, 24)); // NOI18N
        lblClose.setForeground(new java.awt.Color(255, 255, 255));
        lblClose.setText("X");
        lblClose.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                lblCloseMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                lblCloseMouseExited(evt);
            }
            public void mousePressed(java.awt.event.MouseEvent evt) {
                lblCloseMousePressed(evt);
            }
        });
        jPanel1.add(lblClose, new org.netbeans.lib.awtextra.AbsoluteConstraints(600, 10, -1, 20));

        lblTitle.setBackground(new java.awt.Color(0, 0, 0));
        lblTitle.setFont(new java.awt.Font("Tempus Sans ITC", 1, 18)); // NOI18N
        lblTitle.setForeground(new java.awt.Color(255, 255, 255));
        lblTitle.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        lblTitle.setText("WaterSupplySystem");
        lblTitle.setOpaque(true);
        lblTitle.addMouseMotionListener(new java.awt.event.MouseMotionAdapter() {
            public void mouseDragged(java.awt.event.MouseEvent evt) {
                lblTitleMouseDragged(evt);
            }
        });
        lblTitle.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mousePressed(java.awt.event.MouseEvent evt) {
                lblTitleMousePressed(evt);
            }
        });
        jPanel1.add(lblTitle, new org.netbeans.lib.awtextra.AbsoluteConstraints(0, 0, 630, 40));

        lblMoisture.setFont(new java.awt.Font("Tahoma", 1, 14)); // NOI18N
        jPanel1.add(lblMoisture, new org.netbeans.lib.awtextra.AbsoluteConstraints(70, 100, -1, -1));

        btnAllOff.setText("StoreDB");
        btnAllOff.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnAllOffActionPerformed(evt);
            }
        });
        jPanel1.add(btnAllOff, new org.netbeans.lib.awtextra.AbsoluteConstraints(520, 240, -1, -1));

        jButton3.setText("Remove Schedules");
        jButton3.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton3ActionPerformed(evt);
            }
        });
        jPanel1.add(jButton3, new org.netbeans.lib.awtextra.AbsoluteConstraints(460, 280, -1, -1));

        jButton4.setText("Shedule");
        jButton4.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton4ActionPerformed(evt);
            }
        });
        jPanel1.add(jButton4, new org.netbeans.lib.awtextra.AbsoluteConstraints(380, 280, -1, -1));

        jButton5.setText("Turn Off All Motors");
        jButton5.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton5ActionPerformed(evt);
            }
        });
        jPanel1.add(jButton5, new org.netbeans.lib.awtextra.AbsoluteConstraints(390, 120, 180, -1));

        jButton6.setText("Turn On All Motors");
        jButton6.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton6ActionPerformed(evt);
            }
        });
        jPanel1.add(jButton6, new org.netbeans.lib.awtextra.AbsoluteConstraints(390, 90, 180, -1));

        jLabel7.setBorder(javax.swing.BorderFactory.createTitledBorder("Motors"));
        jPanel1.add(jLabel7, new org.netbeans.lib.awtextra.AbsoluteConstraints(360, 60, 240, 110));

        lblAlert.setFont(new java.awt.Font("Tahoma", 1, 14)); // NOI18N
        jPanel1.add(lblAlert, new org.netbeans.lib.awtextra.AbsoluteConstraints(210, 100, -1, -1));

        lblAlert2.setFont(new java.awt.Font("Tahoma", 1, 12)); // NOI18N
        jPanel1.add(lblAlert2, new org.netbeans.lib.awtextra.AbsoluteConstraints(280, 200, -1, -1));

        lblAutoMoisture.setForeground(new java.awt.Color(102, 102, 102));
        lblAutoMoisture.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        lblAutoMoisture.setText("Automatic moisture control: OFF");
        lblAutoMoisture.setCursor(new java.awt.Cursor(java.awt.Cursor.HAND_CURSOR));
        lblAutoMoisture.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                lblAutoMoistureMouseClicked(evt);
            }
        });
        jPanel1.add(lblAutoMoisture, new org.netbeans.lib.awtextra.AbsoluteConstraints(70, 140, 250, -1));

        jLabel2.setBorder(javax.swing.BorderFactory.createTitledBorder("Moisture Status"));
        jPanel1.add(jLabel2, new org.netbeans.lib.awtextra.AbsoluteConstraints(40, 60, 300, 110));

        lblTemperature.setFont(new java.awt.Font("Ubuntu", 0, 15)); // NOI18N
        jPanel1.add(lblTemperature, new org.netbeans.lib.awtextra.AbsoluteConstraints(40, 200, -1, -1));

        lblHumidity.setFont(new java.awt.Font("Ubuntu", 0, 15)); // NOI18N
        jPanel1.add(lblHumidity, new org.netbeans.lib.awtextra.AbsoluteConstraints(40, 230, -1, -1));

        lblRain.setFont(new java.awt.Font("Ubuntu", 0, 15)); // NOI18N
        jPanel1.add(lblRain, new org.netbeans.lib.awtextra.AbsoluteConstraints(40, 260, -1, -1));

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.PREFERRED_SIZE, 626, javax.swing.GroupLayout.PREFERRED_SIZE)
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, 332, Short.MAX_VALUE)
        );

        pack();
        setLocationRelativeTo(null);
    }// </editor-fold>//GEN-END:initComponents

    void storeSensorJSONValueToServer(String val) {
        try {
            URL url = new URL(serverPath + "/index.php/motor/storeSensorData/" + val);
            url.openStream();
        } catch (java.net.ConnectException ex) {
            JOptionPane.showMessageDialog(rootPane, "No connection\nPlease connect to the server", "Connection Error", JOptionPane.ERROR_MESSAGE);
            System.exit(0);
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(rootPane, ex, "Error", JOptionPane.ERROR_MESSAGE);
            System.exit(0);
        }
    }

    void storeSensorJSONValueToServer(String key, String val) {
        try {
            String jdata = key + "=" + val;     //m1=12&m2=45&m3=78 ... Pass like this
            URL url = new URL(serverPath + "/index.php/motor/getMoistureLevel/" + jdata);
            url.openStream();
        } catch (java.net.ConnectException ex) {
            JOptionPane.showMessageDialog(rootPane, "No connection\nPlease connect to the server", "Connection Error", JOptionPane.ERROR_MESSAGE);
            System.exit(0);
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(rootPane, ex, "Error", JOptionPane.ERROR_MESSAGE);
            System.exit(0);
        }
    }

    void storeDatabaseToFile() {
        try {
            String pth = serverPath + "/data/data.json";
            URL u = new URL(pth);
            InputStream in = u.openStream();
            JsonParser jp = new JsonParser();
            JsonElement je = jp.parse(new InputStreamReader(in));

            Gson gs = new GsonBuilder().setPrettyPrinting().create();

            FileWriter fw = new FileWriter("data.json");
            gs.toJson(je, fw);
            System.out.println("Database Stored to data.json");
            fw.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    void getConfig() {
        Properties prop = new Properties();
        FileInputStream input = null;
        try {
            input = new FileInputStream("system_config.logixica");
            prop.load(input);

            System.out.println(prop.getProperty("User"));
            System.out.println(activationKey = prop.getProperty("ActivationKey"));
            System.out.println(serverPath = prop.getProperty("ServerPath"));
            System.out.println(logDuration = Integer.parseInt(prop.getProperty("LogDuration")));
            if (!activationKey.equals("ADMINLogixica99")) {
                JOptionPane.showMessageDialog(rootPane, "System Activation Failed\nPlease enter valid activation code in config file", "Activation", JOptionPane.WARNING_MESSAGE);
                System.exit(0);
            }
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(rootPane, "Error in System configuration", "Error", JOptionPane.ERROR_MESSAGE);
        } finally {
            try {
                input.close();
            } catch (IOException ex) {
                ex.printStackTrace();
            }
        }
    }

    void saveConfig() {
        Properties prop = new Properties();
        OutputStream output = null;

        try {

            output = new FileOutputStream("system_config.logixica");

            prop.setProperty("User", "Admin");
            prop.setProperty("ActivationKey", "ADMINLogixica99");
            prop.setProperty("ServerPath", "http://localhost/watersupply");
            // save properties to project root folder
            prop.store(output, null);

        } catch (Exception io) {
            io.printStackTrace();
        } finally {
            try {
                output.close();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    void getCommandsFromServer() {

    }

    JsonElement getJsonElementFromDatabase(String pth) {
        try {
            //URL u = new URL(pth);
            FileInputStream in = new FileInputStream(pth);
            InputStreamReader inr = new InputStreamReader(in);
            JsonParser jp = new JsonParser();
            JsonElement je = jp.parse(inr);
            in.close();
            inr.close();
            return je;
        } catch (Exception e) {
            e.printStackTrace();
            return null;
        }
    }

    /**
     * Set Automatic Schedule
     *
     * @param time time to start. Date format must be like yyyy-MM-dd HH:mm:ss
     * @param cStart command for start the motor
     * @param cStop command for stop the motor
     * @param delay delay time (seconds)
     */
    Timer setAutomaticShedule(String time, String cStart, String cStop, int delay) {
        try {

            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            Date dt = sdf.parse(time);
            System.out.println(dt.toString());

            t = new Timer("auto");
            TimerTask ts = new TimerTask() {
                @Override
                public void run() {
                    serialWriteToDevice(cStop);
                    System.out.println("MotorStopped");
                }
            };
            TimerTask tt = new TimerTask() {
                @Override
                public void run() {
                    serialWriteToDevice(cStart);
                    System.out.println("MotorStarted");
                    t.schedule(ts, (delay * 1000));
                }
            };
            if (dt.after(new Date())) {             //c.compareTo(new GregorianCalendar())>0
                t.schedule(tt, dt);
                System.out.println("Time Sheduled.." + dt);
            } else {
                System.out.println("Time passed..");
            }
            return t;
        } catch (Exception e) {
            e.printStackTrace();
            return null;
        }
    }

    void setAutomaticSheduleForAll() {
        try {
            String pth = "data.json";                   //Shedule Watering
            JsonElement je = getJsonElementFromDatabase(pth);
            JsonObject root = je.getAsJsonObject();
            JsonArray crop = root.getAsJsonArray("crops");
            Iterator<JsonElement> it = crop.iterator();

            while (it.hasNext()) {
                JsonObject obj = it.next().getAsJsonObject();

                int frequent_day = obj.get("frequent_days").getAsInt();
                String mid = obj.get("motor").getAsString();

                String mCode = "";
                double mFlowRate = 0.0;
                int mAngle = 0;

                JsonArray motors = root.getAsJsonArray("motors");
                Iterator<JsonElement> iter_m = motors.iterator();
                while (iter_m.hasNext()) {
                    JsonObject obj1 = iter_m.next().getAsJsonObject();
                    String x = obj1.get("m_id").getAsString();;
                    if (x.equals(mid)) {
                        mCode = obj1.get("m_code").getAsString();
                        mFlowRate = obj1.get("flowrate").getAsDouble();
                        mAngle = obj1.get("m_angle").getAsInt();
                        break;
                    }
                }
                JsonArray water = obj.get("water").getAsJsonArray();
                System.out.println(water);
                Iterator<JsonElement> iter_c = water.iterator();
                while (iter_c.hasNext()) {
                    JsonObject obj1 = iter_c.next().getAsJsonObject();
                    int litre = obj1.get("litre").getAsInt();
                    String time = obj1.get("time").getAsString();

                    int delay = (int) (litre / mFlowRate);           //Calculation Time by Q=V/t equation

                    if (!time.matches("([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]")) {
                        time += ":00";
                    }

                    Calendar c = new GregorianCalendar();
                    String date = c.get(Calendar.YEAR) + "-" + (c.get(Calendar.MONTH) + 1) + "-" + c.get(Calendar.DAY_OF_MONTH) + " " + time;

                    String cStart = mCode + String.format("%03d", mAngle);
                    String cStop = mCode + "000";

                    //call shedule function
                    setAutomaticShedule(date, cStart, cStop, delay);
                }
            }

        } catch (Exception e) {
            JOptionPane.showMessageDialog(rootPane, e.toString(), "Error", JOptionPane.ERROR_MESSAGE);
        }

    }

    void removeAllSchedules() {
        if (t == null) {
            System.out.println("No timers yet");
        } else {
            t.cancel();
            System.out.println("AllSchedulesRemoved");
        }
    }
    private int motorcount = 2;

    void setAllMotorsOff() {
        char aa = 'a';
        for (int i = 0; i < motorcount; i++) {
            String command = aa++ + "000";
            serialWriteToDevice(command);
        }
        System.out.println("All Motors are stopped due to 100% moisture");

    }

    void setAllMotorsOn() {
        char aa = 'a';
        for (int i = 0; i < motorcount; i++) {
            String command = aa++ + "090";
            serialWriteToDevice(command);
        }
        System.out.println("All Motors are started");
    }

    void serialWriteToDevice(String command) {
        try {
            this.arduino.serialWrite(command, 4, 100);
        } catch (Exception e) {
            JOptionPane.showMessageDialog(rootPane, "Error in serial communication.\nPlease check the device connection");
        }
    }


    private void btnAllOffActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnAllOffActionPerformed
        storeDatabaseToFile();
    }//GEN-LAST:event_btnAllOffActionPerformed

    private void lblCloseMousePressed(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_lblCloseMousePressed
        this.t1.stop();
        setDeviceOnline(this.arduino.getPortDescription(), "off");
        Frame[] fr = getFrames();
        fr[0].setVisible(true);
        this.setVisible(false);
    }//GEN-LAST:event_lblCloseMousePressed
    int lx, ly;
    private void lblCloseMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_lblCloseMouseEntered
        lblClose.setForeground(Color.RED);
    }//GEN-LAST:event_lblCloseMouseEntered

    private void lblCloseMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_lblCloseMouseExited
        lblClose.setForeground(Color.WHITE);
    }//GEN-LAST:event_lblCloseMouseExited

    private void lblTitleMousePressed(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_lblTitleMousePressed
        lx = evt.getX(); //OnScreen();
        ly = evt.getY();//OnScreen();
    }//GEN-LAST:event_lblTitleMousePressed

    private void lblTitleMouseDragged(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_lblTitleMouseDragged
        int lxx = evt.getXOnScreen() - lx;
        int lyy = evt.getYOnScreen() - ly;
        this.setLocation(lxx, lyy);
    }//GEN-LAST:event_lblTitleMouseDragged

    private void jButton3ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton3ActionPerformed
        removeAllSchedules();
    }//GEN-LAST:event_jButton3ActionPerformed

    private void jButton4ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton4ActionPerformed
        setAutomaticSheduleForAll();
    }//GEN-LAST:event_jButton4ActionPerformed

    private void jButton5ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton5ActionPerformed
        setAllMotorsOff();
    }//GEN-LAST:event_jButton5ActionPerformed

    private void jButton6ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton6ActionPerformed
        setAllMotorsOn();
    }//GEN-LAST:event_jButton6ActionPerformed
    int autoMoisture = 0;
    private void lblAutoMoistureMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_lblAutoMoistureMouseClicked
        if (autoMoisture == 0) {
            autoMoisture = 1;
            lblAutoMoisture.setText("Automatic moisture control: ON");
        } else {
            autoMoisture = 0;
            lblAutoMoisture.setText("Automatic moisture control: OFF");
        }
    }//GEN-LAST:event_lblAutoMoistureMouseClicked

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        /* Set the Nimbus look and feel */
        //<editor-fold defaultstate="collapsed" desc=" Look and feel setting code (optional) ">
        /* If Nimbus (introduced in Java SE 6) is not available, stay with the default look and feel.
         * For details see http://download.oracle.com/javase/tutorial/uiswing/lookandfeel/plaf.html 
         */
        try {
            for (javax.swing.UIManager.LookAndFeelInfo info : javax.swing.UIManager.getInstalledLookAndFeels()) {
                if ("Nimbus".equals(info.getName())) {
                    javax.swing.UIManager.setLookAndFeel(info.getClassName());
                    break;
                }
            }
        } catch (ClassNotFoundException ex) {
            java.util.logging.Logger.getLogger(MainUI.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(MainUI.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(MainUI.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(MainUI.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                MainUI dd = new MainUI();
                dd.setDefaultCloseOperation(HIDE_ON_CLOSE);
                dd.setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton btnAllOff;
    private javax.swing.JButton jButton3;
    private javax.swing.JButton jButton4;
    private javax.swing.JButton jButton5;
    private javax.swing.JButton jButton6;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel7;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JLabel lblAlert;
    private javax.swing.JLabel lblAlert2;
    private javax.swing.JLabel lblAutoMoisture;
    private javax.swing.JLabel lblClose;
    private javax.swing.JLabel lblHumidity;
    private javax.swing.JLabel lblMoisture;
    private javax.swing.JLabel lblRain;
    private javax.swing.JLabel lblTemperature;
    private javax.swing.JLabel lblTitle;
    // End of variables declaration//GEN-END:variables
}
