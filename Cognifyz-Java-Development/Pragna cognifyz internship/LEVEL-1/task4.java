import java.util.Scanner;
import java.util.Random;

public class task4{
    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);
        
        System.out.println("Random Password Generator:");
        

        System.out.print("Enter the  length of the password: ");
        int length = sc.nextInt();
        
        
        System.out.print("have to include numbers: ");
        boolean Numbers = sc.next().toLowerCase().startsWith("y");
        
        System.out.print("have to include lowercase letters: ");
        boolean Lowercase = sc.next().toLowerCase().startsWith("y");
        
        System.out.print(" have to include uppercase letters: ");
        boolean Uppercase = sc.next().toLowerCase().startsWith("y");
        
        System.out.print("have to include special characters: ");
        boolean SpecialChars = sc.next().toLowerCase().startsWith("y");
        
       
        String password = gpwd(length, Numbers,Lowercase, Uppercase, SpecialChars);
        
    
        System.out.println("Your randomly generated password is: " + password);
        
        sc.close();
    }
    
    public static String gpwd(int length, boolean Numbers, boolean Lowercase, boolean Uppercase, boolean SpecialChars) 
    {
        String lowercaseChars = "abcdefghijklmnopqrstuvwxyz";
        String uppercaseChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        String numberChars = "0123456789";
        String specialChars = "!@#$%^&*()-_+=~`[]{}|;:,.<>?";
        
        String ch = "";
        if (Lowercase) {
            ch += lowercaseChars;
        }
        if (Uppercase) {
            ch += uppercaseChars;
        }
        if (Numbers) {
            ch += numberChars;
        }
        if (SpecialChars) {
            ch += specialChars;
        }
        
        Random random = new Random();
        char[] password = new char[length];
        
        for (int i = 0; i < length; i++) {
            int randomIndex = random.nextInt(ch.length());
            password[i] = ch.charAt(randomIndex);
        }
        
        return new String(password);
    }
}

