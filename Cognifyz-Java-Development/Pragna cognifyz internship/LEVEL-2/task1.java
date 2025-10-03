import java.util.Scanner;

public class task1{
    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);
        char[][] board = new char[3][3];
        
        System.out.println("Welcome to Tic-Tac-Toe!");
        System.out.println("Player 1 (X) - Player 2 (O)");
        
        boolean playagn = true;
        while (playagn) {
            initializeBoard(board);
            boolean player1 = true;
            boolean gwon = false;
            int mvs = 0;
            
            while (!gwon && mvs < 9) {
                ptbrd(board);
                int row, col;
                if (player1) {
                    System.out.println("Player 1's turn X:");
                } else {
                    System.out.println("Player 2's turn O:");
                }
                
                System.out.print("Enter row : ");
                row = sc.nextInt();
                System.out.print("Enter column : ");
                col = sc.nextInt();
                
                if (isValidMove(board, row, col)) {
                    char sym = player1 ? 'X' : 'O';
                    board[row][col] = sym;
                    player1 = !player1;
                    mvs++;
                    if (chkwin(board, sym)) {
                        gwon = true;
                        ptbrd(board);
                        System.out.println("Player " + (player1 ? "2" : "1") + " ( " + sym + " ) wins!");
                    }
                } else {
                    System.out.println("Invalid move. Please try again.");
                }
            }
            
            if (!gwon) {
                ptbrd(board);
                System.out.println("It's a draw!");
            }
            
            System.out.print("Do you want to play again? (yes/no): ");
            String playAgainResponse = sc.next().toLowerCase();
            playagn = playAgainResponse.equals("yes");
        }
        
        System.out.println("Thanks for playing Tic-Tac-Toe!");
        sc.close();
    }
    
    public static void initializeBoard(char[][] board) {
        for (int i = 0; i < 3; i++) {
            for (int j = 0; j < 3; j++) {
                board[i][j] = '-';
            }
        }
    }
    
    public static void ptbrd(char[][] board) {
        System.out.println("  0 1 2");
        for (int i = 0; i < 3; i++) {
            System.out.print(i + " ");
            for (int j = 0; j < 3; j++) {
                System.out.print(board[i][j] + " ");
            }
            System.out.println();
        }
    }
    
    public static boolean isValidMove(char[][] board, int row, int col) {
        return row >= 0 && row < 3 && col >= 0 && col < 3 && board[row][col] == '-';
    }
    
    public static boolean chkwin(char[][] board, char sym) {
   
        for (int i = 0; i < 3; i++) {
            if ((board[i][0] == sym && board[i][1] == sym && board[i][2] == sym) ||
                (board[0][i] == sym && board[1][i] == sym && board[2][i] == sym)) {
                return true;
            }
        }
        
     
        return (board[0][0] == sym && board[1][1] == sym && board[2][2] == sym) ||
               (board[0][2] == sym && board[1][1] == sym && board[2][0] == sym);
    }
}
