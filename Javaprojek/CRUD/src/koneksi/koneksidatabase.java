/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package koneksi;

/**
 *
 * @author LAB RPL PC 01
 */
import java.sql.*;
import javax.swing.JOptionPane;

public class koneksidatabase {
    Connection cn;
    public static Connection BukaKoneksi(){
        try {
           Class.forName("com.mysql.cj.jdbc.Driver"); // jika masih ingin eksplisit
            Connection cn = DriverManager.getConnection("jdbc:mysql://localhost/latihan", "root", "");
                  return cn;
                    } catch (Exception e) {
                        JOptionPane.showMessageDialog(null, e);
                        return null;
        }
    }
}