/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package util;

import arduino.Arduino;
import arduino.PortDropdownMenu;
import java.awt.Color;
import java.awt.Frame;
import javax.swing.JOptionPane;

/**
 *
 * @author Muznious
 */
public class ControllerUI extends javax.swing.JFrame {

    private Arduino arduino;
    private PortDropdownMenu jcb;
    private int[] baudRates = {300, 1200, 2400, 4800, 9600, 19200, 38400, 57600, 74880, 115200, 230400, 250000};

    public final static String ARDUINO_BOARD = "ArduinoMega2560x";
    
    public ControllerUI() {
        initComponents();
        jcb = new PortDropdownMenu();
        jcb.refreshMenu();
        jcbPorts.setModel(jcb.getModel());
        for (int i = 0; i < baudRates.length; jcbBaudRate.addItem(baudRates[i]), i++);
        jcbBaudRate.setSelectedItem(9600);
        lblMsg.setText("Not Connected!");
        lblMsg.setForeground(Color.blue);
        btnStartApp.setEnabled(false);
    }

    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        jPanel1 = new javax.swing.JPanel();
        btnConnect = new javax.swing.JButton();
        jcbPorts = new javax.swing.JComboBox();
        btnRefreshPorts = new javax.swing.JButton();
        jcbBaudRate = new javax.swing.JComboBox();
        btnStartApp = new javax.swing.JButton();
        lblMsg = new javax.swing.JLabel();
        btnExit = new javax.swing.JButton();
        lblTitle = new javax.swing.JLabel();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("Connect to Arduino");
        setUndecorated(true);
        setResizable(false);

        jPanel1.setBackground(new java.awt.Color(255, 255, 255));
        jPanel1.setBorder(javax.swing.BorderFactory.createLineBorder(new java.awt.Color(0, 0, 0)));
        jPanel1.setLayout(new org.netbeans.lib.awtextra.AbsoluteLayout());

        btnConnect.setText("Connect");
        btnConnect.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnConnectActionPerformed(evt);
            }
        });
        jPanel1.add(btnConnect, new org.netbeans.lib.awtextra.AbsoluteConstraints(310, 100, 110, -1));

        jcbPorts.setModel(new javax.swing.DefaultComboBoxModel(new String[] { "Select Port" }));
        jcbPorts.setToolTipText("Serial Port");
        jPanel1.add(jcbPorts, new org.netbeans.lib.awtextra.AbsoluteConstraints(50, 100, 130, -1));

        btnRefreshPorts.setText("Refresh");
        btnRefreshPorts.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnRefreshPortsActionPerformed(evt);
            }
        });
        jPanel1.add(btnRefreshPorts, new org.netbeans.lib.awtextra.AbsoluteConstraints(50, 130, -1, -1));

        jcbBaudRate.setToolTipText("Baud Rate");
        jPanel1.add(jcbBaudRate, new org.netbeans.lib.awtextra.AbsoluteConstraints(190, 100, 110, -1));

        btnStartApp.setText("Start App");
        btnStartApp.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnStartAppActionPerformed(evt);
            }
        });
        jPanel1.add(btnStartApp, new org.netbeans.lib.awtextra.AbsoluteConstraints(310, 150, 110, 30));

        lblMsg.setFont(new java.awt.Font("Tahoma", 0, 13)); // NOI18N
        jPanel1.add(lblMsg, new org.netbeans.lib.awtextra.AbsoluteConstraints(50, 70, -1, 20));

        btnExit.setFont(new java.awt.Font("Tempus Sans ITC", 1, 20)); // NOI18N
        btnExit.setForeground(new java.awt.Color(255, 255, 255));
        btnExit.setText("X");
        btnExit.setContentAreaFilled(false);
        btnExit.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                btnExitMouseEntered(evt);
            }
            public void mouseExited(java.awt.event.MouseEvent evt) {
                btnExitMouseExited(evt);
            }
        });
        btnExit.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnExitActionPerformed(evt);
            }
        });
        jPanel1.add(btnExit, new org.netbeans.lib.awtextra.AbsoluteConstraints(400, 0, 50, 40));

        lblTitle.setBackground(new java.awt.Color(0, 0, 0));
        lblTitle.setFont(new java.awt.Font("Tahoma", 0, 18)); // NOI18N
        lblTitle.setForeground(new java.awt.Color(255, 255, 255));
        lblTitle.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        lblTitle.setText("SerialPortController");
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
        jPanel1.add(lblTitle, new org.netbeans.lib.awtextra.AbsoluteConstraints(0, 0, 450, 40));

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, 219, Short.MAX_VALUE)
        );

        pack();
        setLocationRelativeTo(null);
    }// </editor-fold>//GEN-END:initComponents

    private void btnConnectActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnConnectActionPerformed
        try {
            if (btnConnect.getText().equals("Connect")) {
                String port = jcbPorts.getSelectedItem().toString();
                if (!port.isEmpty()) {
                    arduino = new Arduino(port, (int) jcbBaudRate.getSelectedItem());
                    if (arduino.openConnection()) {
                        btnConnect.setText("Disconnect");
                        enableComponents(false);
                        System.out.println();
                        lblMsg.setText("Connected to " + arduino.getPortDescription() + " with " + arduino.getSerialPort().getBaudRate() + " baud rate");
                        lblMsg.setForeground(Color.decode("#007700"));
                    }
                }
            } else {
                arduino.closeConnection();
                btnConnect.setText("Connect");
                enableComponents(true);
            }
        } catch (Exception e) {
            JOptionPane.showMessageDialog(rootPane, "Serial Connection Error\nPlease check the device and connection", "Error", JOptionPane.ERROR_MESSAGE);
        }
    }//GEN-LAST:event_btnConnectActionPerformed

    private void enableComponents(boolean flag) {
        jcbPorts.setEnabled(flag);
        jcbBaudRate.setEnabled(flag);
        btnStartApp.setEnabled(!flag);
        if (flag) {
            lblMsg.setText("Not Connected!");
            lblMsg.setForeground(Color.BLUE);
        }
    }

    private void btnRefreshPortsActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnRefreshPortsActionPerformed
        jcb.refreshMenu();
        jcbPorts.setModel(jcb.getModel());
    }//GEN-LAST:event_btnRefreshPortsActionPerformed

    private void btnExitActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnExitActionPerformed
        btnConnectActionPerformed(evt);
        System.exit(0);
    }//GEN-LAST:event_btnExitActionPerformed

    private void btnStartAppActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnStartAppActionPerformed
        boolean st = true;
        for (Frame f : getFrames()) {
//            System.out.println(f.getTitle());
            if (f.getName().equals("ExhiWebApp") && f.isActive()) {
                f.setVisible(true);
                f.setExtendedState(0);
                f.toFront();
                st = false;
                break;
            }
        }
        if (st) {
            new MainUI(arduino).setVisible(true);
            this.setVisible(false);
        }
    }//GEN-LAST:event_btnStartAppActionPerformed
    int xx, yy;
    private void lblTitleMousePressed(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_lblTitleMousePressed
        xx = evt.getX();
        yy = evt.getY();
    }//GEN-LAST:event_lblTitleMousePressed

    private void lblTitleMouseDragged(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_lblTitleMouseDragged
        this.setLocation(evt.getXOnScreen() - xx, evt.getYOnScreen() - yy);
    }//GEN-LAST:event_lblTitleMouseDragged

    private void btnExitMouseEntered(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_btnExitMouseEntered
        btnExit.setForeground(Color.RED);
    }//GEN-LAST:event_btnExitMouseEntered

    private void btnExitMouseExited(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_btnExitMouseExited
        btnExit.setForeground(Color.WHITE);
    }//GEN-LAST:event_btnExitMouseExited

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
            java.util.logging.Logger.getLogger(ControllerUI.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(ControllerUI.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(ControllerUI.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(ControllerUI.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                new ControllerUI().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton btnConnect;
    private javax.swing.JButton btnExit;
    private javax.swing.JButton btnRefreshPorts;
    private javax.swing.JButton btnStartApp;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JComboBox jcbBaudRate;
    private javax.swing.JComboBox jcbPorts;
    private javax.swing.JLabel lblMsg;
    private javax.swing.JLabel lblTitle;
    // End of variables declaration//GEN-END:variables
}
