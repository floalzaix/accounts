# Accounts

1. Feetures 

    What will the app contain ?

   * [x] Login => Password (Encrypted) *Page*
   * [ ] Create an account *Page*
   * [ ] Create a savings account *Page*
   * [ ] Create a bills account *Page*
   * [ ] To input data in these accounts *Page*
   * [ ] To have summury feedback (Balance and then per month the revenues and expencies + details if wanted *button detail*) *Page*
   * [ ] To have a global feedback of all accounts *Page*
   * [ ] A menu to switch pages

---

2. The futur possible upgrades 


    * [ ] To be able to add an account from an Excel container
    * [ ] To access directly data of an account through an API
    * [ ] To incorporate AI to help speed things up
    * [ ] An admin acces to manage the users
    * [ ] Mapping of errors with diplaying on the page => modif de la page err404 en error puis param : nbr
    * [ ] Forgotten password

---

3. Architecture 

    The project will be implemented with a **MVC** (Model View Controller) architecture.

    > Acces to the pages will be done by a **Router**

    > A **Interaction Handler** will manage the feedback to the user regarding the small messages

    > The views engine will be **Plates**

---

4. Login

    > The goal of the page is to offer security to the user's data and a way to access it

    #### Features

     * [x] A field for the user name *form*
     * [x] A field for the password *form*
     * [ ] A link if the password was forgotten *page* and then *action* for later
     * [x] A link to register *page*

    > The DB Scheme :

    ```mermaid
            erDiagram
                users {
                    VARCHAR(50) id PK
                    VARCHAR(100) name "NOT NULL UNIQUE"
                    VARCHAR(256) hash "NOT NULL"
                }
    ```

