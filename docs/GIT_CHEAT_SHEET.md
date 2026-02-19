# Git Cheat Sheet - Branch: ahamris

## Current Branch Status
```bash
git status                    # Check current status
git branch                    # List all branches (current marked with *)
```

## Viewing Changes
```bash
git diff                      # Show unstaged changes
git diff --staged             # Show staged changes
git log --oneline -10         # Last 10 commits
git log --graph --oneline     # Visual commit history
```

## Making Changes
```bash
git add .                     # Stage all changes
git add <file>                # Stage specific file
git commit -m "message"       # Commit staged changes
git commit -am "message"      # Stage & commit all tracked files
```

## Undoing Changes
```bash
git restore <file>            # Discard unstaged changes
git restore --staged <file>   # Unstage file
git reset HEAD~1              # Undo last commit (keep changes)
git reset --hard HEAD~1       # Undo last commit (discard changes)
```

## Working with Remote
```bash
git pull origin ahamris       # Pull latest from remote
git push origin ahamris       # Push to remote
git fetch origin              # Fetch remote changes
```

## Branch Operations
```bash
git checkout ahamris          # Switch to ahamris branch
git checkout -b new-branch    # Create & switch to new branch
git branch -d branch-name     # Delete local branch
```

## Stashing
```bash
git stash                     # Save uncommitted changes
git stash list                # List stashes
git stash pop                 # Apply & remove last stash
git stash drop                # Delete last stash
```

## Quick Workflow
```bash
# Standard workflow
git status                    # Check what changed
git add .                     # Stage changes
git commit -m "description"   # Commit
git push origin ahamris       # Push to remote
```

## Emergency Commands
```bash
git clean -fd                 # Remove untracked files/folders
git reset --hard origin/ahamris  # Reset to remote state
```

