import java.util.Scanner;

public class task2{
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        
        System.out.println("Welcome to Password Strength Checker!");
        System.out.println("Please enter your password:");
        String password = scanner.nextLine();
        
        int length = password.length();
        boolean hasUppercase = hasUppercase(password);
        boolean hasLowercase = hasLowercase(password);
        boolean hasDigit = hasDigit(password);
        boolean hasSpecialChar = hasSpecialChar(password);
        
        int score = calculateScore(length, hasUppercase, hasLowercase, hasDigit, hasSpecialChar);
        
        System.out.println("Password strength:");
        if (score >= 5) {
            System.out.println("Very strong");
        } else if (score >= 3) {
            System.out.println("Strong");
        } else if (score >= 2) {
            System.out.println("Moderate");
        } else {
            System.out.println("Weak");
        }
        
        scanner.close();
    }
    
    public static boolean hasUppercase(String password) {
        for (char c : password.toCharArray()) {
            if (Character.isUpperCase(c)) {
                return true;
            }
        }
        return false;
    }
    
    public static boolean hasLowercase(String password) {
        for (char c : password.toCharArray()) {
            if (Character.isLowerCase(c)) {
                return true;
            }
        }
        return false;
    }
    
    public static boolean hasDigit(String password) {
        for (char c : password.toCharArray()) {
            if (Character.isDigit(c)) {
                return true;
            }
        }
        return false;
    }
    
    public static boolean hasSpecialChar(String password) {
        for (char c : password.toCharArray()) {
            if (!Character.isLetterOrDigit(c)) {
                return true;
            }
        }
        return false;
    }
    
    public static int calculateScore(int length, boolean hasUppercase, boolean hasLowercase, boolean hasDigit, boolean hasSpecialChar) {
        int score = 0;
        if (length >= 8) score++;
        if (hasUppercase) score++;
        if (hasLowercase) score++;
        if (hasDigit) score++;
        if (hasSpecialChar) score++;
        return score;
    }
}

