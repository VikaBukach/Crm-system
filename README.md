******     Початок роботи з CRM системою:    ******


* Зліва внизу відкрийте меню акаунта, оберіть SIGN IN та пройдіть реєстрацію у формі Register here.
  
* Виконайте авторизацію у формі SIGN IN - Authorization.
  
* На сторінці Home відображається календар, де будуть показані задачі, створені користувачем.
  
* У розділі Users знаходиться список користувачів, де можна створювати, редагувати та видаляти користувачів.

* У розділі Role є вже створені ролі користувачів CRM системи з їх описом та правами.

* У розділі Pages відображаються сторінки з доступом для користувачів. Сторінки системи можна додавати, редагувати доступи та видаляти.

* У розділі Quiz є список вікторин, а в розділі Create quiz можна створити власну вікторину. Вікторина інтегрована у Telegram бот і налаштована для відправки двох вікторин у чат щодня за допомогою cron.

* У розділі To do list спочатку потрібно створити категорії у Todo Category для задач з описом, які можна редагувати та приховувати.
  
* У розділі Task create можна створити задачу, обрати категорію, до якої вона належатиме, та призначити дату завершення. Створені задачі відображаються у списку.
  
* При кліку на задачу можна переглянути її, змінити статус та редагувати. У формі редагування можна вносити зміни, такі як визначення пріоритету задачі, додавання опису та вибір нагадування. Додавання тегів, які допомагають користувачам швидше знаходити необхідну інформацію в задачах.

* Завершені задачі потрапляють у розділ Completed, а прострочені — в Expired, але їх можна продовжувати редагувати.

* У меню акаунта зареєстрованого користувача (де відображається імейл) перейдіть до Profile, щоб зв’язати CRM систему з Telegram ботом.

* Виконайте всі вказані дії, щоб інтегрувати бота для нагадувань і комунікації з користувачами. Нагадування про завершення задач відбуваються за допомогою cron.
  

****! Для демонстрації системи відкрито майже всі доступи для користувачів. За задумом CRM системи лише супер-адмін матиме повний доступ до всіх сторінок, їх створення, редагування та видалення. 

* Користувачі матимуть доступ лише до створення, перегляду та редагування власних задач.*
  

  
****** Getting started with the CRM system: ******

In the bottom left, open the account menu, select SIGN IN, and register using the Register here form.

Complete the authorization in the SIGN IN - Authorization form.

On the Home page, a calendar displays the tasks created by the user.

In the Users section, you will find a list of users where you can create, edit, and delete users.

In the Role section, predefined CRM user roles with descriptions and permissions are available.

In the Pages section, pages with user access are displayed. You can add, edit access, and delete pages in the system.

In the Quiz section, there is a list of quizzes, and in the Create quiz section, you can create your own quiz. The quiz is integrated with the Telegram bot and set up to send two quizzes to the chat daily using cron.

In the To do list section, you first need to create categories in Todo Category for tasks with descriptions, which can be edited or hidden later.

In the Task create section, you can create a task, choose the category it belongs to, and assign a due date. Created tasks are displayed in a list.

Clicking on a task allows you to view, update the status, and edit it. In the edit form, you can make changes such as setting task priority, adding a description, and choosing a reminder. You can also add tags to help users quickly find the necessary information within tasks.

Completed tasks are listed in the Completed section, and overdue tasks are in the Expired section, but they remain editable.

In the account menu of a registered user (where the email is displayed), go to Profile to link the CRM system with the Telegram bot.

Follow the instructions to integrate the bot for reminders and communication with users. Task completion reminders are sent via cron.

****! For demonstration purposes, almost all accesses are open to users. The intended design of the CRM system is that only the super admin will have full access to all pages, their creation, editing, and deletion.

Users will have access only to create, view, and edit their own tasks.*
