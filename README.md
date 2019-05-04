# Some important issues:
- For auth use login `admin` and password `admin`
- No CSRF protection, because form doesn't change anything. Only possible attack on this form is DoS, but I ignore it, because this is just test.
- All request to API in UTC time, because no info in docs
- **It's only MVP**
- Minimal test coverage

#### Task:
There is a JSON API below with 10 companies. Each of them have 5 employees and each employee worked everyday last year for few hours.

Please build a form where you can search each employee by name and get their workhours between selected dates.

Group workhours by month to show month by month how many hours they worked.

In another view show a list of companies sorted by workhours logged in decending order. With an option to reverse the order.


#### Checklist
- Must be OOP :white_check_mark:
- Use Laravel and Vus JS if possible :heavy_exclamation_mark: _symfony used because Laravel is not marked as required_
- Solution must look presentable :white_check_mark: - https://www.dropbox.com/s/t4et08jeneuo6bd/Screenshot%202019-05-05%2000.34.55.png?dl=0
- Form must have date range selection :white_check_mark: - https://www.dropbox.com/s/cz5gh65p5671dwo/Screenshot%202019-05-05%2000.35.42.png?dl=0
- Data fetching and grouping must be done on server side :white_check_mark:
- Cache querys so that the same query will not be done twice :white_check_mark: _`All query to API is cached in filesystem, with infinite TTL (Cache querys so that the same query will not be done twice)_
- Data that gets sent to frontend must be structured on server side :white_check_mark:
- Show proper error messages if applicable :white_check_mark: https://www.dropbox.com/s/v0s2iw8lrw7epgx/Screenshot%202019-05-05%2000.54.09.png?dl=0
- Must use GIT repo (GitLab, GitHub, BitBucket) :white_check_mark:
- Share the repo with us when you are ready with the task :white_check_mark:
