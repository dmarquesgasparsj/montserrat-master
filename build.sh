#!/bin/bash
export PATH="$PATH:$(yarn global bin)"
yarn install # install all the dependencies
npm run dev # build the project