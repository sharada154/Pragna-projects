import java.util.Scanner;

public class task1{

    public static double farenheit_conversion(double c) {
        double temp = (c * 9 / 5) + 32;
        return temp;
    }

    public static double celsius_conversion(double f) {
       double temp = (f - 32) * 5 / 9;
       return temp;
    }

    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);

        System.out.print("Enter the Temperature value: ");
        double temp_value = sc.nextDouble();

        System.out.print("Enter the unit of measurement: ");
        String temp_unit = sc.next().toUpperCase();

        double temp_conversion;
        if (temp_unit.equals("C")) {
           temp_conversion = farenheit_conversion(temp_value);
            System.out.printf(temp_value+"°C is converted to "+temp_conversion+"°F");
        } else if (temp_unit.equals("F")) {
           temp_conversion = celsius_conversion(temp_value);
            System.out.printf(temp_value+"°F is converted to "+temp_conversion+"°C");
        } else {
            System.out.println("Invalid temp_unit");
        }

        sc.close();
    }
}
