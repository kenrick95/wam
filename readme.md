wam (for Wikipedia Asian Month)
=====
[Wikipedia Asian Month](https://meta.wikimedia.org/wiki/Wikipedia_Asian_Month) Tracking and Judging Tool.


## Plans
### Tracking new articles of particpants
- Need list of participating Wikipedia
- Need a page on that local Wikipedia storing the list of participating users
    + get_page_content --> basic parsing or use Parsoid? --> participating user list
- For each user,
    + get_new_pages_of_user --> ibid --> article list of each user

### Judging
- Need list of participating Wikipedia
- Also need a page listing the judges --> should be protected OR use MediaWiki namespace OR use my user js namespace
    + get_page_content --> magic --> list of judges
- OAuth to login/logout
    + to authenticate the user
- Using article list of each user,
    + Show a form to real judge with checklists containing the criteria
    + Judge will be shown a page of article list
    + Judge will tick one checkbox if the article fulfills the criterion
    + Make it easy to judge: article shown in iframe while checklist floats around?

### Others
Do PHP unit testing if possible
- https://phpunit.de/getting-started.html