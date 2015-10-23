wam (for Wikipedia Asian Month)
=====
[Wikipedia Asian Month](https://meta.wikimedia.org/wiki/Wikipedia_Asian_Month) Tracking and Judging Tool.

## Features
- Progress tracking of participants, showing page size (in bytes), approximate word count, and judge verdict
- Judging process of participants' articles, via OAuth.
  * Judging result will be saved on meta-wiki page: `Wikipedia Asian Month/Judging/WIKI/USERNAME` where `WIKI` is formatted as XX.wikipedia.org (e.g. "id.wikipedia.org")


## How to use

The tool has two functionalities:

1.  Checking participants' progress and status of their articles
2.  Judging participants' articles.

### Check progress

1.  At the home of the tool, on the navigation bar at the top, click
  “Check progress”.
2.  Select the wiki
3.  Select the user
4.  Now you will see the article list of the selected user on the
  selected wiki created during the period of Wikipedia Asian Month.
  You can see the:
  -   Date and time that article is created
  -   Byte count of the article (page size)
  -   Approximate word count

      :   If you want to know the approximate word count of a
          particular article, click “check word count”
      :   If you want to know the approximate word count of all
          pending articles, at the table header, click “check word
          count for pending articles”

  -   Status of the article (as given by the judge)

      :   If judge result is not available, it will be “no” for those
          articles with byte count less than 3,500 and “pending” for
          the rest of the unjudged articles

### Judging articles

1.  At the home of the tool, on the navigation bar at the top, click
  “Judging”.
2.  Authorize the OAuth process
3.  Select the wiki
4.  Select the user
5.  Now you will see the article list of the selected user on the
  selected wiki created during the period of Wikipedia Asian Month.
  You can see the details as seen in “Check progress” section above.
6.  Click “Judge” on articles you want to judge
7.  Now you will see a page containing the article in mobile-version
  Wikipedia, with some helper statistics such as:
  -   Byte count
  -   Approximate word count

  :   and also a three-button group (*yes*, *no*, and *pending*) at
      the right side of the page.
  :   If previously the article has been judged, the current
      status/verdict will be highlighted as if it was pressed
      (*active*)

8.  Select your verdict to be given to the article (*yes*, *no*, or
  *pending*)
9.  Done

### Random notes

-   [Recent changes in meta-wiki on judge giving out verdict](https://meta.wikimedia.org/w/index.php?title=Special:RecentChanges&tagfilter=OAuth+CID%3A+317)
