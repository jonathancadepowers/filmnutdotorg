# Step 1. Setup & Configuration

Load the configuration file, which is structured as followed:

```
Facilities
  └─ Facility (e.g., South Campus)
      └─ Field (e.g., SC1)
          ├─ Division Priorities (Order in Which Divisions Should be Assigned to Fields, e.g., 7U, 8U, 9U)
          └─ Time Slots Available
              ├─ Monday: 6:00pm, 7:30pm
              ├─ Tuesday: 6:00pm, 7:30pm
              └─ Thursday: 6:00pm, 7:30pm

Divisions
  └─ Division Name (e.g., 7U)
      ├─ Season Start Date:
      ├─ Season End Date:
      └─ Teams Listed By Name:

Blackout Dates
  └─ Dates on Which No Games Can Be Scheduled For Any Division/Facility
  ```

*Note:* The program implicitly assumes all times are expressed in CST. 

# Step 2: Identify Available Slots

Build a dictionary of all available "slots" across all fields, organized by week. Do not yet schedule any games.

Example Output:

```
slots_by_week = {
    38: [
        (date(2025-09-15), Field(SC1), "18:00", Facility(South Campus)),
        (date(2025-09-15), Field(SC1), "19:30", Facility(South Campus)),
        (date(2025-09-16), Field(SC1), "18:00", Facility(South Campus)),
        (date(2025-09-16), Field(SC1), "19:30", Facility(South Campus)),
        (date(2025-09-17), Field(SC1), "18:00", Facility(South Campus)),
        (date(2025-09-17), Field(SC1), "19:30", Facility(South Campus)),
        (date(2025-09-18), Field(SC1), "19:30", Facility(South Campus)),  # No 6pm - used for clinics
        (date(2025-09-15), Field(SC2), "18:00", Facility(South Campus)),
        (date(2025-09-15), Field(SC2), "19:30", Facility(South Campus)),
        # ... hundreds more slots for SC2, SC3, SC4, SC5, McGovern, etc.
    ]
    # And so on for all additional weeks. 
    # The starting week is calculated from the earliest (min) `begin_date` across all divisions in the .yml file. 
    # The ending week is calculated from the latest (max) `end_date` across all divisions in the .yml file.
```

# Step 3: Allocate Slots to Divisions

Build a flat dictionary (not organized by week or any other grouping) which assigns slots to divisions. Do not yet schedule any games

This step adheres to the following rules/assumptions:

- **Division Demand for Games:** Games needed per week for a division = ( number of teams ÷ 2 )
- **Process One Week at a Time:** Example: Don't fill all of Week 38 before moving to week 39. Instead, process Week 38 fully, then Week 39, then week 40, etc. This ensures fair distribution across the entire season.
- **Ignore Inactive Divisions:** If a division's start/end date doesn't fall within a week, don't assign it any slots for the week in question.
- **Prioritization Logic:** When assigning a division to a slot, the program follows a three-level priority hierarchy (honored in the order listed below): 
  - *Continuity:* If a division is already using a field during the current *week* and still needs games, give them the slot in question.
  - *Same-Day Preference:* Avoid assigning different divisions to the same field on the same day when possible.
  - *Division Priorities:* Consult the field's `division_priorities` values in the configuration file in order and assign slots to the first division that needs games.
  *Note:* The top-order in which facilities/fields are listed within the configuration file also impacts the prioritization logic. Example: The first field listed will be processed first.

Example Output:

```
slot_assignments = {
    # Week 38 - Only 10U and 12U are active (7U, 8U, 9U start Sept 22)
    ("SC1", date(2025-09-15), "18:00"): "10U",
    ("SC1", date(2025-09-15), "19:30"): "10U",
    ("SC1", date(2025-09-16), "18:00"): "10U",
    ("SC1", date(2025-09-16), "19:30"): "10U",
    ("SC1", date(2025-09-17), "18:00"): "10U",
    ("SC1", date(2025-09-17), "19:30"): "10U",
    ("SC1", date(2025-09-18), "19:30"): "12U",  # 10U satisfied, 12U gets remainder
    
    ("SC2", date(2025-09-15), "18:00"): "10U",
    ("SC2", date(2025-09-15), "19:30"): "12U",  # 10U satisfied from SC1+SC2
    ("SC2", date(2025-09-16), "18:00"): "12U",
    ("SC2", date(2025-09-16), "19:30"): "12U",
    ("SC2", date(2025-09-17), "18:00"): "12U",
    ("SC2", date(2025-09-17), "19:30"): "",      # 12U satisfied, slot empty
    ("SC2", date(2025-09-18), "18:00"): "",
    ("SC2", date(2025-09-18), "19:30"): "",
    
    ("SC3", date(2025-09-15), "18:00"): "",      # All divisions satisfied
    ("SC3", date(2025-09-15), "19:30"): "",
    # ... rest of SC3, SC4, SC5 are empty for week 38
    
    # Week 39 - Now 7U, 8U, 9U, 10U, 12U all active (Sept 22-23 are blackout)
    ("SC1", date(2025-09-24), "18:00"): "7U",
    ("SC1", date(2025-09-24), "19:30"): "7U",
    ("SC1", date(2025-09-25), "18:00"): "7U",
    ("SC1", date(2025-09-25), "19:30"): "7U",
    ("SC1", date(2025-09-26), "19:30"): "7U",    # No Wed 6pm (clinics)
    
    ("SC2", date(2025-09-24), "18:00"): "7U",    # 7U still needs games
    ("SC2", date(2025-09-24), "19:30"): "8U",    # 7U satisfied, 8U next priority
    ("SC2", date(2025-09-25), "18:00"): "8U",
    ("SC2", date(2025-09-25), "19:30"): "8U",
    ("SC2", date(2025-09-26), "18:00"): "8U",
    ("SC2", date(2025-09-26), "19:30"): "8U",
    
    ("SC3", date(2025-09-24), "18:00"): "9U",
    ("SC3", date(2025-09-24), "19:30"): "9U",
    ("SC3", date(2025-09-25), "18:00"): "9U",
    ("SC3", date(2025-09-25), "19:30"): "9U",
    # ... And so on
```

# Step 4: Assign Each Slot to Two Teams (aka: Schedule Games)

For each slot that has a division assigned, decide which two specific teams from that division will play each other. Home versus away designations are *not* yet set.

When scheduling games, the following prioritization logic is followed:

- **Teams that Haven't Yet Played this Week:** Every team should play once per week. This is a hard filter—teams that have already played this week are completely excluded from consideration for additional games.
- **Opponent Diversity:** Pair up teams that haven't played each other yet this season. The algorithm strongly prioritizes new matchups and will only allow repeat matchups when all possible new pairings have been exhausted. This is the most heavily weighted scoring criterion.
- **Balance Total Game Counts:** Among teams that satisfy the above criteria, prefer teams with fewer total games played so far this season. This acts as a secondary scoring factor to keep all teams roughly even in total games played.
- **Day/Time Balance:** Try to ensure no team always plays on the same day or always plays at the same time; spread games fairly across the week and across time slots. This is a lower-weighted optimization that provides a small scoring bonus to improve overall fairness.

Example Output:

```
opponent_assignments = {
    # Week 38 - Only 10U and 12U are active
    ("SC1", date(2025-09-15), "18:00"): ("10U", "Anacondas", "Boas"),
    ("SC1", date(2025-09-15), "19:30"): ("10U", "Cobras", "Copperheads"),
    ("SC1", date(2025-09-16), "18:00"): ("10U", "Diamondbacks", "Kingsnakes"),
    ("SC1", date(2025-09-16), "19:30"): ("10U", "Mambas", "Moccasins"),
    ("SC1", date(2025-09-17), "18:00"): ("10U", "Pythons", "Rattlers"),
    ("SC1", date(2025-09-17), "19:30"): ("10U", "Serpents", "Sidewinders"),
    ("SC1", date(2025-09-18), "19:30"): ("12U", "Cardinals", "Eagles"),
    
    ("SC2", date(2025-09-15), "18:00"): ("10U", "Vipers", "Anacondas"),  # Anacondas' 2nd game? No! Different week logic applies
    ("SC2", date(2025-09-15), "19:30"): ("12U", "Falcons", "Hawks"),
    ("SC2", date(2025-09-16), "18:00"): ("12U", "Orioles", "Owls"),
    ("SC2", date(2025-09-16), "19:30"): ("12U", "Phoenix", "Swifts"),
    ("SC2", date(2025-09-17), "18:00"): ("12U", "Cardinals", "Hawks"),  # Cardinals plays again - OK, different day
    ("SC2", date(2025-09-17), "19:30"): ("", "", ""),  # Empty slot
    ("SC2", date(2025-09-18), "18:00"): ("", "", ""),
    ("SC2", date(2025-09-18), "19:30"): ("", "", ""),
    
    # Week 39 - All divisions active (Sept 22-23 blackout)
    ("SC1", date(2025-09-24), "18:00"): ("7U", "Blizzard", "Cyclones"),
    ("SC1", date(2025-09-24), "19:30"): ("7U", "Earthquakes", "Fireballs"),
    ("SC1", date(2025-09-25), "18:00"): ("7U", "Hurricanes", "Lightning"),
    ("SC1", date(2025-09-25), "19:30"): ("7U", "Thunder", "Tornadoes"),
    ("SC1", date(2025-09-26), "19:30"): ("7U", "Whirlwind", "Wildfire"),
    
    ("SC2", date(2025-09-24), "18:00"): ("7U", "Blizzard", "Earthquakes"),  # Wait, Blizzard plays twice?
    # Actually NO - this would violate "haven't played this week" - let me correct:
    
    ("SC2", date(2025-09-24), "18:00"): ("8U", "Aliens", "Comets"),
    ("SC2", date(2025-09-24), "19:30"): ("8U", "Cosmos", "Eclipse"),
    ("SC2", date(2025-09-25), "18:00"): ("8U", "Galaxy", "Meteors"),
    ("SC2", date(2025-09-25), "19:30"): ("8U", "Moonbeams", "Orbits"),
    ("SC2", date(2025-09-26), "18:00"): ("8U", "Satellites", "Stars"),
    ("SC2", date(2025-09-26), "19:30"): ("8U", "Sunburst", "Supersonic"),
    
    ("SC3", date(2025-09-24), "18:00"): ("9U", "Badgers", "Bears"),
    ("SC3", date(2025-09-24), "19:30"): ("9U", "Bobcats", "Cheetahs"),
    ("SC3", date(2025-09-25), "18:00"): ("9U", "Cougars", "Foxes"),
    ("SC3", date(2025-09-25), "19:30"): ("9U", "Grizzlies", "Jaguars"),
    ("SC3", date(2025-09-26), "18:00"): ("9U", "Leopards", "Lions"),
    ("SC3", date(2025-09-26), "19:30"): ("9U", "Panthers", "Polar Bears"),
    ("SC3", date(2025-09-27), "18:00"): ("9U", "Pumas", "Tigers"),
    
    # ... continues for all weeks and divisions
}
```

# Step 5: Assign Home/Away Designations

For each game that has two teams assigned, decide which team is "home" and which team is "away."

The following logic/rules are applied:

- **Balance Each Team's Home/Away Ratio:** Every team should have roughly equal home and away games.
- **Process Games Chronologically**: Assign home/away in date/time order to maintain consistency.

Sample Output:

```
# Now the left-right order in which games are listed in the output matters: first time listed is home, second is away
final_assignments = {
    # Week 38 - Only 10U and 12U are active
    ("SC1", date(2025-09-15), "18:00"): ("10U", "Anacondas", "Boas"),        # Anacondas HOME, Boas AWAY
    ("SC1", date(2025-09-15), "19:30"): ("10U", "Copperheads", "Cobras"),    # Copperheads HOME, Cobras AWAY
    ("SC1", date(2025-09-16), "18:00"): ("10U", "Diamondbacks", "Kingsnakes"),
    ("SC1", date(2025-09-16), "19:30"): ("10U", "Moccasins", "Mambas"),      # Moccasins HOME, Mambas AWAY
    ("SC1", date(2025-09-17), "18:00"): ("10U", "Pythons", "Rattlers"),
    ("SC1", date(2025-09-17), "19:30"): ("10U", "Sidewinders", "Serpents"),  # Sidewinders HOME, Serpents AWAY
    ("SC1", date(2025-09-18), "19:30"): ("12U", "Cardinals", "Eagles"),
    
    ("SC2", date(2025-09-15), "18:00"): ("10U", "Anacondas", "Vipers"),      # Anacondas HOME again? NO - see note below
    ("SC2", date(2025-09-15), "19:30"): ("12U", "Falcons", "Hawks"),
    ("SC2", date(2025-09-16), "18:00"): ("12U", "Owls", "Orioles"),          # Owls HOME, Orioles AWAY
    ("SC2", date(2025-09-16), "19:30"): ("12U", "Phoenix", "Swifts"),
    ("SC2", date(2025-09-17), "18:00"): ("12U", "Hawks", "Cardinals"),       # Hawks HOME, Cardinals AWAY (Cardinals away this time)
    # And so on... 
```

# Step 6: Eliminate Bye Weeks

Within divisions that have an odd number of teams, the schedule generated in the previous step contains bye weeks for some teams (e.g., a division with 13 teams can only play 6 games in a week, leaving one team out). To ensure that every team (in every division) plays at least 1 game per week (never more or less), the schedule is modified during this step. Additional games are inserted into the schedule to ensure every team plays every week. This can result in some teams (within the same division) playing a different number of games within a season and some teams playing 2 games in a single week. If a division has an even number of teams, the division is skipped during this step.

Key rules/assumptions followed:

* To eliminate bye weeks, empty slots (unfilled during previous steps listed above) are used.
* Each team plays extra games in AT MOST ONE week per season.
* When scheduling bye elimination games, the program prefers new matchups (team that have not yet played each other).

Sample Output: Same as the previous step above (into `final_assignments`) but with additional games inserted.

# Step 7: Make Optimization Tweaks to the Schedule

Make changes to the generated schedule with the following goals in mind:

- **Home/Away Balance:** Each team should have roughly equal home and away games (ideally 50/50 split).
- **Day Balance:** Each team should play on a variety of days throughout the week (not all Mondays or all Thursdays).
- **Time Balance:** Each team should play at a variety of times (mix of 6pm and 7:30pm games, not always early or always late).

When attempting to satisfy the above goals, the following constraints are honored:

- **Matchups never change:** Example: If Blizzard plays Cyclones, that pairing stays (only home/away or day/time might change).
- **Same week only:** Day/time swaps only happen within the same week (don't move a Week 39 game to Week 40).
- **Same division only:** Day/time swaps only happen between games in the same division. Example: never move a 7U game into an 8U slot (or vice versa).

Sample Output: Same as the previous step above (into `final_assignments`) but with slot data modified.

# Step 8: Output the Final CSV File

Take the completed, optimized schedule (within `final_assignments`) and write it out as a CSV file.

Important: When the CSV file is output, there may be games that are needed but could not be scheduled due to insufficient field capacity. That is, there are not enough available time slots across all fields to meet total weekly demand, due to: blackout dates eliminating available days; field priority mismatches where fields won't accept divisions that need slots; field continuity constraints blocking optimal allocation; bye elimination requiring extra games with no empty slots available; or divisions having misaligned active weeks compared to available capacity. When this occurs, the CSV file contains rows where two teams are paired together to play each other, but the slot has no field, date, or time, as shown here: `40,,,,,,,7U,Thunder,Tornadoes`.

In addition to the CSV file that is generated to disk, the command line will produce output similar to:

```
$ python scheduler.py --config=fall-2025.yml > schedule.csv

INFO: Loading configuration from fall-2025.yml
INFO: Generating available slots by week
INFO: Found 847 total available slots across 7 weeks
INFO: Allocating slots to divisions (week by week)
INFO: Week 38: Allocated 10 games
INFO: Week 39: Allocated 28 games
INFO: Week 40: Allocated 28 games
WARNING: Week 40: 7U needs 5 games but only got 3 (2 unscheduled)
INFO: Week 41: Allocated 28 games
INFO: Week 42: Allocated 28 games
INFO: Week 43: Allocated 28 games
INFO: Week 44: Allocated 28 games
INFO: Pairing teams for scheduled slots
INFO: Assigning home/away designations
INFO: Eliminating bye weeks for odd-team divisions
INFO: 10U: Added 7 bye-elimination games
INFO: Optimizing schedule
INFO: Home/away optimization: 12 swaps made
INFO: Day/time optimization: 8 swaps made
INFO: Outputting schedule to CSV
INFO: Total games scheduled: 231
INFO: Games by division:
INFO:   7U: 38 games
INFO:   8U: 48 games
INFO:   9U: 56 games
INFO:   10U: 52 games
INFO:   12U: 37 games
WARNING: 3 games could not be scheduled due to insufficient capacity
ERROR: SCHEDULE VALIDATION FAILED: 3 games could not be scheduled
```






