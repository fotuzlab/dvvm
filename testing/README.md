DVVM AUTOMATION
===============

Available Tags
--------------
@1
@2
@3
@frontend
@javascript
@sanity

Run the tests
-------------
1. Start required services by running command `dvvm.behat.start`
2. To run the automation, navigate to */testing* directory
3. Run `behat features -p headless_chrome`. For more options, run `behat -h`
4. Testing report can be accessed at http://test.local.dvvm.dvdev.net
5. To stop the drivers run `dvvm.behat.stop`. This will free up resources on the VM. No other side effects to let them running.

Access testing reports
----------------------
http://test.local.dvvm.dvdev.net


