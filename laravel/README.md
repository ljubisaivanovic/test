

# Known issues

- If the client doesn't set the `Accept` header to `application/json` while trying to create new posts/comments, the application's validator will try to redirect to the same page to display errors instead of returning them as JSON.
