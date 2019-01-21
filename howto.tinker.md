1. Start an authenticated session --> auth()->loginUsingId(2)

2. Ok, now let's add an event listener:
    ```php
    DB::listen(function ($query) { dump($query->sql); dump($query->bindings); dump($query->time); });
    ```
    That's all. Now all queries will be displayed in this session.

3. Some useful commands:

    |   Command     |	Description                                                                 | Alias |
    |---------------|-------------------------------------------------------------------------------|-------|
    |   help        |   Show a list of commands. Type help [foo] for information about [foo].       |   ?   |
    |   ls          |   List local, instance or class variables, methods and constants.             | list, dir |
    |   dump        |   Dump an object or primitive.	                                            |       |
    |   doc         |   Read the documentation for an object, class, constant, method or property.  | rtfm, man |
    |   show        |   Show the code for an object, class, constant, method or property.           |       |	
    |   wtf         |   Show the backtrace of the most recent exception.                            | last-exception, wtf? |
    |   whereami    |   Show where you are in the code.                                             |       |
    |   trace       |   Show the current call stack.	                                            |       |
    |   throw-up    |   Throw an exception out of the Psy Shell.	                                |       |
    |   buffer      |	Show (or clear) the contents of the code input buffer.                      | buf   |
    |   clear       |   Clear the Psy Shell screen.	                                                |       |
    |   history     |	Show the Psy Shell history.	hist                                            |       |
    |   exit        |	End the current session and return to caller.                               | quit, q |
    |   clear-compiled  |	Remove the compiled class file.                                         |       |
    |   down        |	Put the application into maintenance mode.	                                |       |
    |   env         |	Display the current framework environment.	                                |       |
    |   optimize    |   Optimize the framework for better performance.	                            |       |
    |   up          |	Bring the application out of maintenance mode.	                            |       |
    |   migrate     |	Run the database migrations.                                                |       |
    |   inspire     |	Display an inspiring quote.                                                 |       |

