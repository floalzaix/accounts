# Accounts

1. Feetures 

    What will the app contain ?

   * [x] Login => Password (Encrypted) *Page*
   * [ ] Create an account *Page*
   * [ ] Create a savings account *Page*
   * [ ] Create a bills account *Page*
   * [ ] To input data in these accounts *Page*
   * [ ] Category system that will include a tree organisation of the categories
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
    * [ ] A display with arrows for the categories
    * [ ] Optimisation and cleaning of the category system => the category class and DAO

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

---

5. Accounts

    > The purpose of those pages is to add accounts of different types

    #### Features

     * [x] A field for the name of the account
     * [x] A field for the level of categories
     * [x] Link with the database DAO objects

    > The DB Scheme :

    ```mermaid
            erDiagram
                users {
                    VARCHAR(50) id PK
                    VARCHAR(100) name "NOT NULL UNIQUE"
                    VARCHAR(256) hash "NOT NULL"
                }
                accounts {
                    VARCHAR(50) id PK
                    VARCHAR(50) id_user FK
                    VARCHAR(100) name "NOT NULL"
                    TINYINT nb_of_categories "NOT NULL"
                }
                transactions {
                    VARCHAR(50) id PK
                    VARCHAR(50) id_account FK
                    DATE date "NOT NULL"
                    VARCHAR(100) title 
                    DATE bank_date 
                    INT amount "NOT NULL"
                }
                categories {
                    VARCHAR(50) id PK
                    VARCHAR(50) id_account FK
                    VARCHAR(50) name "NOT NULL"
                    VARCHAR(50) id_parent FK
                }
                transactions_categories {
                    VARCHAR(50) id_transaction FK 
                    VARCHAR(50) id_categorie FK
                }
    ```

---

1. Data analysis (summary feedback)

    > The aim of this page is to have a list of the accounts an then to be able to access one and to add data or to check the summary

    #### Features

     * [ ] Home Page with the account list *page*
     * [ ] A category *page*
     * [ ] A *view* which can be parameterized to 
       * [ ] add data
       * [ ] remove data
       * [ ] edit data
       * [ ] summarize data per month and balance
     * [ ] A style CSS with the options summary, input on the left

---

7. The categories system

    > Intended to organize the categories of the expenses and the incomes

    #### Features

     * [x] A input method for each level of categorie (For instance, a subcategory is a level 2 category)
     * [x] A graph feedback to see in form of a chart the categories => Maybe *mermaid*
     * [x] Handle the DB part.

    > The DB Scheme for the categories

    ```mermaid
            erDiagram
                categories_level {
                    VARCHAR(50) id_cat FK
                    TINYINT level "NOT NULL CHECK (level BETWEEN 0 AND 10)"
                }
                cat_hierarchy {
                    VARCHAR(50) id_cat_parent FK
                    VARCHAR(50) id_cat_child FK
                }
    ```