import java.io.File;
import java.io.FileWriter;
import java.io.FileReader;
import java.io.BufferedReader;
import java.io.IOException;
import java.util.Scanner;

public class task3{
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        
        System.out.println("Welcome to File Encryption/Decryption!");
        System.out.println("Choose an option:");
        System.out.println("1. Encrypt");
        System.out.println("2. Decrypt");
        int option = scanner.nextInt();
        
        scanner.nextLine(); // Consume newline
        
        System.out.println("Enter the file name or path:");
        String filePath = scanner.nextLine();
        
        System.out.println("Enter the encryption/decryption key:");
        int key = scanner.nextInt();
        
        if (option == 1) {
            encryption(filePath, key);
            System.out.println("File encrypted successfully!");
        } else if (option == 2) {
            decryption(filePath, key);
            System.out.println("File decrypted successfully!");
        } else {
            System.out.println("Invalid option selected!");
        }
        
        scanner.close();
    }
    
    public static void encryption(String filePath, int key) {
        try {
            FileReader fileReader = new FileReader(filePath);
            BufferedReader bufferedReader = new BufferedReader(fileReader);
            StringBuilder encryptedtxt = new StringBuilder();
            int character;
            while ((character = bufferedReader.read()) != -1) {
                if (Character.isLetter(character)) {
                    char encryptedChar = (char)(((int)character + key - 65) % 26 + 65);
                    encryptedtxt.append(encryptedChar);
                } else {
                    encryptedtxt.append((char)character);
                }
            }
            bufferedReader.close();
            FileWriter fileWriter = new FileWriter("encrypted_" + filePath);
            fileWriter.write(encryptedtxt.toString());
            fileWriter.close();
        } catch (IOException e) {
            System.out.println("An error occurred while encrypting the file.");
            e.printStackTrace();
        }
    }
    
    public static void decryption(String filePath, int key) {
        try {
            FileReader fileReader = new FileReader(filePath);
            BufferedReader bufferedReader = new BufferedReader(fileReader);
            StringBuilder decryptedText = new StringBuilder();
            int character;
            while ((character = bufferedReader.read()) != -1) {
                if (Character.isLetter(character)) {
                    char decryptedChar = (char)(((int)character - key - 65 + 26) % 26 + 65);
                    decryptedText.append(decryptedChar);
                } else {
                    decryptedText.append((char)character);
                }
            }
            bufferedReader.close();
            FileWriter fileWriter = new FileWriter("decrypted_" + filePath);
            fileWriter.write(decryptedText.toString());
            fileWriter.close();
        } catch (IOException e) {
            System.out.println("An error occurred while decrypting the file.");
            e.printStackTrace();
        }
    }
}
