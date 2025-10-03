import java.util.Scanner;
public class task3{
public static void main(String[]args) {
    Scanner sc = new Scanner(System.in);
    System.out.println("Enter number of grades to be entered for a student:");
    int n = sc.nextInt();
    double[]num_grades = new double[n];
    System.out.println("Enter each grade of a student:");
    for(int i = 0;i<n;i++)
    {

         num_grades[i] = sc.nextDouble();
    }
    double total = 0.0;
    for(int i = 0;i<n;i++)
    {
       
        total = total+num_grades[i];
    }
    double avg = (total)/n;
    System.out.println("Total ="+ total);
    System.out.println("Average grade in percentage="+ avg);
    if(avg>=90)
    {
        System.out.println("LetterGrade = A");
    }
    else if(avg>=80)
    {
        System.out.println("LetterGrade=B");
    }
    else if(avg>=70)
    {
        System.out.println("LetterGrade=C");
    }
    else if(avg>=60)
    {
        System.out.println("LetterGrade=D");
    }
    else if(avg>=50)
    {
        System.out.println("LetterGrade=E");
    }
    else
    {
        System.out.println("LetterGrade=F");
    }
}
    
}
