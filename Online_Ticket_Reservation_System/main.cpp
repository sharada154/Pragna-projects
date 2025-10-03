#include <iostream>
#include <fstream>
#include <string>
using namespace std;

class Event {
public:
    string name;
    int price;
    Event(string n, int p) { name = n; price = p; }
};

class Ticket {
public:
    static int ticketCounter;  // for unique Ticket IDs
    int ticketID;
    string userName;
    int age;
    Event event;
    int ticketPrice;

    Ticket(string name, int a, Event e) : event(e) {
        ticketID = ++ticketCounter;  // auto-generate Ticket ID
        userName = name;
        age = a;
        ticketPrice = (age < 12) ? event.price / 2 : event.price;  // age-based pricing
    }

    void saveTicket() {
        ofstream file("tickets.txt", ios::app);
        if (!file) {
            cout << "Error opening file!\n";
            return;
        }
        file << "Ticket ID: " << ticketID
             << ", Name: " << userName
             << ", Age: " << age
             << ", Event: " << event.name
             << ", Price: $" << ticketPrice << endl;
        file.close();
    }

    void printTicket() {
        cout << "\n----- Ticket -----\n";
        cout << "Ticket ID: " << ticketID
             << "\nName: " << userName
             << "\nAge: " << age
             << "\nEvent: " << event.name
             << "\nPrice: $" << ticketPrice << endl;
        cout << "-----------------\n";
    }
};
int Ticket::ticketCounter = 0;  // initialize counter

int main() {
    Event events[] = { Event("Movie Night", 100), Event("Concert", 200), Event("Theater Play", 150) };
    int numEvents = 3;

    cout << "Welcome to Online Ticket Reservation System!\n";

    int n;
    cout << "How many tickets do you want to book? ";
    while (!(cin >> n) || n <= 0) {
        cout << "Invalid number! Enter again: ";
        cin.clear();
        cin.ignore(10000, '\n');
    }

    for (int i = 0; i < n; i++) {
        cin.ignore();
        string name;
        int age, choice;

        cout << "\nEnter full name for Ticket " << i+1 << ": ";
        getline(cin, name);

        cout << "Enter age: ";
        while (!(cin >> age) || age <= 0 || age > 120) {
            cout << "Invalid age! Enter between 1-120: ";
            cin.clear();
            cin.ignore(10000,'\n');
        }

        cout << "\nAvailable Events:\n";
        for (int j = 0; j < numEvents; j++)
            cout << j+1 << ". " << events[j].name << " ($" << events[j].price << ")\n";

        cout << "Select event (1-" << numEvents << "): ";
        while (!(cin >> choice) || choice < 1 || choice > numEvents) {
            cout << "Invalid choice! Enter 1-" << numEvents << ": ";
            cin.clear();
            cin.ignore(10000,'\n');
        }

        Ticket t(name, age, events[choice-1]);
        t.saveTicket();
        t.printTicket();
        cout << "Payment Successful!\n";
    }

    // View all booked tickets
    cout << "\n--- All Booked Tickets ---\n";
    ifstream file("tickets.txt");
    string line;
    while (getline(file, line)) {
        cout << line << endl;
    }
    file.close();

    return 0;
}
