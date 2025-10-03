import java.util.Scanner;

public class task2{
    public static void main(String[]args)
     {
        Scanner sc = new Scanner(System.in);
        System.out.print("Enter a word or phrase: ");
        String str = sc.nextLine();

        if (isPalindrome(str)) {
            System.out.println("Yes, it's a palindrome!");
        } else {
            System.out.println("No, it's not a palindrome.");
        }
    }

    public static boolean isPalindrome(String s) 
    {
       
        String temp = "";
        for (int i = 0; i < s.length(); i++) 
        {
            char ch = s.charAt(i);
            if (Character.isLetterOrDigit(ch)) {
                temp += Character.toLowerCase(ch);
            }
        }

    
        String rev = "";
        for (int i = temp.length() - 1; i >= 0; i--) {
            rev += temp.charAt(i);
        }

        return temp.equals(rev);
    }
}

