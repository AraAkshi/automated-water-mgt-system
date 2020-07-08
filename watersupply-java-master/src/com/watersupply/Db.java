/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.watersupply;

import java.sql.Connection;
import java.sql.DriverManager;


/**
 *
 * @author HP
 */
public class Db {
    
    
    public Connection getConnection(){
        try {
            Class.forName("com.mysql.jdbc.Driver");
            Connection c=DriverManager.getConnection("jdbc:mysql://localhost:3306/db_krishlands", "root", "");
            System.out.println(c.getCatalog());
            
        } catch (Exception ex) {
            System.out.println(ex.toString());
        }
        return null;
    }
    
    void test(){
        
        int x=1;
        while(x<=10){
        
            if(x>=5){
                System.out.println("TRUE");
            }else{
                System.out.println("FALSE");
            }
            x++;
        }
    
    }
    
    
    
    public static void main(String[] args) {
        Db c= new Db();//.getConnection();
        c.getConnection();
        //c.test();
    }
}
