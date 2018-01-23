#!/bin/bash


# If the remote already exits: Check if we have commits to push
# commits=`git log @{u}..`
# if [ -z "$commits" ]; then
# 	echo 'No commits exists, done.';
#     exit 0
# fi


#repoPath=$(git rev-parse --show-toplevel);


###
# check/ ask for a different branch to be pushed (maybe just for temp. needs!?)
#
# list of existing remote branches
brAllowed=`git branch -r | sed 's/.*\///'`;
# current branch
brCurrent=`git rev-parse --abbrev-ref HEAD`;
brCheck=1;
for br in $brAllowed; do
	if [ $br = $brCurrent ]
	then
		brCheck=0;
	fi
done


if [ $brCheck = 1 ]
then
# 	# bash:
 	read -p "You're about to push a new branch \"$brCurrent\", are you sure? (Y/y): " -n 1 -r < /dev/tty
 	if [[ $REPLY =~ ^[Yy]$ ]]
	then
		echo 'ok';
	else
		echo 'abort!';
		exit 1;
	fi
fi



# last check before pushing
echo "You're about to push, are you sure you won't break the build? (Y/y)";
read REPLY;
if [ $(echo "$REPLY" | grep -E '^[Yy]') ]
then
	echo 'ok';
else
	echo 'abort!';
	exit 1;
fi

echo "you may run now: git push $*";
exit 0 # push will execute
