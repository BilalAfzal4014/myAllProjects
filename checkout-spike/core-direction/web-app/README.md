
# Core Direction Web App

This is the code behind the core direction Web App.

### Setup

Install [Node and Npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm), then run:

```shell
npm install
```

### Usage

To start the development server, run:

```shell
npm run serve
```

To build the app for production, run:

```shell
npm run build
```

To run the linter, run:

```shell
npm run lint
```

### Web Apps

- [Production App](https://my.coredirection.com/)
- [Staging App](https://stage-v3-1-0-1.coredirection.com/)

### GitHub Repos

- [Source Code](https://github.com/coredirection/web-app)
- [Pull Requests](https://github.com/coredirection/web-app/pulls)
- [GitHub Actions](https://github.com/coredirection/web-app/actions)

### Dependencies

Dependencies are defined in [package.json](package.json).

### Git Commands

Checkout Staging

```shell
git checkout staging
```

Create Branch

```shell
git branch "ticket-number"
```

Commit Changes

```shell
git add-commit-push "Description of changes"
```

### Git Workflows

Local Development

- create new branch from `staging`
- use ticket number as branch name
- commit changes to your branch
- ensure all linting are passing
- create pull request with ticket number as prefix
- assign pull request for review
- make necessary changes from the review
- review and close all comments
- use `Squash and merge` to merge back into `staging`

Production Release

- create pull request from `staging` to `master`
- assign pull request for review
- use `Create a merge commit` to merge back into `master`

## Code Refactoring Flow

### Create a new page

This step involves creating a copy of each existing page and giving it to the URL. This is necessary to ensure that the old pages remain functional while the new pages are being developed and tested.

### Code update

In this step, we will update the code for each page, ensuring that it is optimized and we create small and reuseable components. We will then test the page locally to ensure that it is working as expected.

### Push to staging environment

Once we have tested the code locally and it is working as expected, we will push the updated code to a staging environment. Here, we will perform additional testing to ensure that everything is still working as expected in a more realistic environment.

### Release it to prod

Once we are satisfied that the code is working as expected in the prod environment, we will remove old page. However, before we do so, we will wait atleast a week to ensure that there are no issues with the new pages.

### Removing old page

Assuming that everything is working as expected with the new pages, we will delete the old pages. This ensures that users are directed to the new pages and that the old pages are not creating any unnecessary confusion or conflicts.

**Note:** If at any point any test had failed, we will be reverting the URL back to the old page and start debugging for new code again. Once we have resolved the issue, we will change the URL back to the new page and follow the above process from point 3 to 5. This ensures that we are only releasing code that has been thoroughly tested and verified to be working as expected.

incase to modify the flow [generate_flowchart.py](./src/docs/generate_flowchart.py).

![Screenshot](./src/docs/refactoring_flowchart.svg)

## Folder Structure

Standardize the following folders for better predictability in addition to the basic file structure that Vue CLI provides. Once we finish the code cleanup, our repo will have these folders.

![Folder Structure](./src/docs/folder_structure.svg)
