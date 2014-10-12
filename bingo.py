#!/usr/bin/env python

import sys
import random
import copy

rows = 18
cols = 9
ntickets = 6

# just a simple function to output the ticket visually
def output_strip(matrix):
    count = 0
    for row in matrix:
        if count % 3 == 0:
            print "--------------------------"
        count += 1
        for cell in row:
            if not cell:
                print '{0:2s}'.format(" "),
            else:
                print '{0:2d}'.format(cell),
        print
    print "--------------------------"

def strip_string(matrix):
    rows = []

    for x in range(0, len(matrix)):
        if x % 3 == 0:
            if len(rows) > 0:
                print "-".join(rows)
            rows = []

        rows.append(';'.join(['' if y is None else str(y) for y in matrix[x]]))

    print "-".join(rows)

# this will get the total number of numbers in a card
def get_items_in_card(card):
    count = 0

    for col in card:
        count += len(col)

    return count

def generate_tickets(numbers):
    # this represents the different cards (6 in total)
    tickets = [ [ [] for _ in range(cols) ] for _ in range(ntickets) ]

    # shuffle the individual number columns
    for x in numbers:
        random.shuffle(x)

    # initially, place at least one number to each column in each card
    for x in range(0, ntickets):
        for y in range(0, cols):
            tickets[x][y].append(numbers[y].pop())

    # just pick a random card and place last 9th column number there
    tickets[random.randint(0, ntickets - 1)][8].append(numbers[8].pop())

    # now perform 4 iterations over columns of numbers and place them randomly
    for x in range(0, 4):
        
        for y in range(0, cols):

            try:
                number = numbers[y].pop()

                choices = range(0, ntickets)

                while 1:
                    # pick a random card
                    ticket = random.choice(choices)

                    # card already full, so skip card
                    if get_items_in_card(tickets[ticket]) == 15:
                        choices.remove(ticket)
                        continue
                    
                    # card already has two numbers in this column, so skip
                    if len(tickets[ticket][y]) == 2 and x < 3:
                        choices.remove(ticket)
                        continue
                    
                    # card already has three numbers in this column, so skip
                    if len(tickets[ticket][y]) == 3 and x == 3:
                        choices.remove(ticket)
                        continue

                    # finally add to card and break the while loop
                    tickets[ticket][y].append(number)
                    break
                    
            # this will be thrown for the 1st column, which has only 3 numbers
            except IndexError:
                continue

    # do this somewhere else
    for ticket in tickets:
        for y in range(0, ntickets):
            ticket[y] = sorted(ticket[y])

    return tickets

def generate_strip(strip, tickets):
    for x in range(0, ntickets):
        rowcount = [0, 0, 0]
        ticket = tickets[x]

        for y in range(0, cols):
            if len(ticket[y]) == 3:
                col = ticket[y]
                strip[x * 3 + 0][y] = col.pop(0)
                strip[x * 3 + 1][y] = col.pop(0)
                strip[x * 3 + 2][y] = col.pop(0)
                rowcount[0] += 1
                rowcount[1] += 1
                rowcount[2] += 1

        # second iteration, all the 2-columns
        count = 0
        # make the order in which we spread the 2-column a bit more random
        for y in range(0, cols):
            if len(ticket[y]) == 2:
                col = ticket[y]
                if count == 0:
                    r = (0, 1)
                    count = 1
                elif count == 1:
                    r = (1, 2)
                    count = 2
                elif count == 2:
                    r = (0, 2)
                    count = 0
                
                strip[x * 3 + r[0]][y] = col.pop(0)
                strip[x * 3 + r[1]][y] = col.pop(0)
                rowcount[r[0]] += 1
                rowcount[r[1]] += 1

        for y in range(0, ntickets):
            if len(ticket[y]) == 1:
                # find the index of the lowest rowcount
                row = rowcount.index(min(rowcount))
                strip[x * 3 + row][y] = ticket[y].pop(0)
                rowcount[row] += 1

    return strip

def main(arg):
    
    # just a static number pool
    pool = [range( 1, 10),
            range(10, 20),
            range(20, 30),
            range(30, 40),
            range(40, 50),
            range(50, 60),
            range(60, 70),
            range(70, 80),
            range(80, 91)]

    # an empty matrix
    # the whole page matrix
    strip_template = [[None] * cols for _ in range(rows)]

    try:
        strips = int(arg[0])
    except IndexError:
        strips = 1
        
    count = 0

    while 1:

        if count == strips:
            break

        tickets = generate_tickets(copy.deepcopy(pool))
        strip = generate_strip(copy.deepcopy(strip_template), tickets)

        count += 1


if __name__ == '__main__':
    main(sys.argv[1:])
