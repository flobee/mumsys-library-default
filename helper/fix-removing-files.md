# Complete remove of files in the git history

E.g files neede to be removed because of security issues


https://stackoverflow.com/questions/43762338/how-to-remove-file-from-git-history

But, take care:

    - Rewrites the history entries (hashes of relevant commits)
    - All local branches must be checked!
    - force push required!
    - all people in the team should pull before they push (maybe forced) back
      where the records/entries can come back again!

To solve it, not pushing sensitiv data to public, maybe forgotten branches
on any cpu/pc come back to the mainline without checking this:
You should setup a git hook to check if these unwanted files came back by any
reason and break the push! At least when going live or public.

Testing:

- clone repo to a # a: you work on to drop file
- clone repo to b # b: for comparing changes
- on both (a and b) checkout the branches (e.g.: master)
- Excecute the action on a
- compare the changes on file system (should be none or wihtout the files to be removed)
- compare the git history (should be some!)
    a: git log --stat > /tmp/a
    b: git log --stat > /tmp/b
    diff /tmp/a /tmp/b
- Then you get a clou what's changed

Action:

    git filter-branch --index-filter "git rm -rf --cached --ignore-unmatch PATH_TO_FILE1 PATH_TO_FILE2" HEAD



https://www.endpoint.com/blog/2012/06/21/moving-commit-to-another-branch-in-git


4-ways-to-remove-files-from-git-commit-history
https://www.sitereq.com/post/4-ways-to-remove-files-from-git-commit-history