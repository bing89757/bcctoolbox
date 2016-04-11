#!/bin/bash
awk -F':' '{ print $1 }' bestbizops.turner.com/bcceng/tv/gettickets.php
