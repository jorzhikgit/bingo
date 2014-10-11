#!/usr/bin/env python

import sys
import random

rows = 18
cols = 9

# just a simple function to output the ticket visually
def output_card(matrix):
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

def card_string(matrix):
    count = 0
    result = ""
    for row in matrix:
        if count % 3 == 0 and result != "":
           print result
           result = ""

        count += 1
        
        if result != "":
            result += "-"
        
        for cell in row:
            if not cell:
                result += ";.;"
            else:
                result += ';' + str(cell) + ';'
    
    print result

# this will get the total number of numbers in a card
def get_items_in_card(card):
    count = 0

    for col in card:
        count += len(col)

    return count

def main(arg):
    # the whole page matrix
    matrix = [[None] * cols for _ in range(rows)]

    # this represents the different cards (6 in total)
    cards = [ [ [] for _ in range(9) ] for _ in range(6) ]
    
    # generate the number seed
    numbers = [range(1,10),
               range(10,20),
               range(20,30),
               range(30,40),
               range(40,50),
               range(50,60),
               range(60,70),
               range(70,80),
               range(80,91)]

    # shuffle the individual number columns
    for x in numbers:
        random.shuffle(x)

    # initially, place at least one number to each column in each card
    for x in range(0, len(cards)):
        for y in range(0, len(numbers)):
            cards[x][y].append(numbers[y].pop())

    # just pick a random card and place last 8th column number there
    cards[random.randint(0, len(cards) - 1)][8].append(numbers[8].pop())

    # now perform 4 iterations over columns of number and place then randomly
    for x in range(0, 4):
        
        for y in range(0, len(numbers)):
            try:
                number = numbers[y].pop()

                choices = range(0, len(cards))

                while 1:
                    # pick a random card
                    card = random.choice(choices)

                    # card already full, so skip card
                    if get_items_in_card(cards[card]) == 15:
                        choices.remove(card)
                        continue
                    
                    # card already has two numbers in this column, so skip
                    if len(cards[card][y]) == 2 and x < 3:
                        choices.remove(card)
                        continue
                    
                    # card already has three numbers in this column, so skip
                    if len(cards[card][y]) == 3 and x == 3:
                        choices.remove(card)
                        continue

                    # finally add to card and break the while loop
                    cards[card][y].append(number)
                    break
                    
            # this will be thrown for the 1st column, which has only 3 numbers
            except IndexError:
                continue

    # do this somewhere else
    for card in cards:
        for y in range(0, len(card)):
            card[y] = sorted(card[y])

    for x in range(0, len(cards)):
        row1 = row2 = row3 = 0

        # first iteration, all the 3 columns
        for y in range(0, len(cards[x])):
            if len(cards[x][y]) == 3:
                matrix[x * 3][y]     = cards[x][y].pop(0)
                matrix[x * 3 + 1][y] = cards[x][y].pop(0)
                matrix[x * 3 + 2][y] = cards[x][y].pop(0)
                row1 += 1
                row2 += 1
                row3 += 1

        # second iteration, all the 2-columns
        count = 0
        # make the order in which we spread the 2-column a bit more random
        for y in range(0, len(cards[x])):
            if len(cards[x][y]) == 2:
                if count == 0:
                    matrix[x * 3][y] = cards[x][y].pop(0)
                    matrix[x * 3 + 1][y] = cards[x][y].pop(0)
                    row1 += 1
                    row2 += 1
                    count = 1
                elif count == 1:
                    matrix[x * 3 + 1][y] = cards[x][y].pop(0)
                    matrix[x * 3 + 2][y] = cards[x][y].pop(0)
                    row2 += 1
                    row3 += 1
                    count = 2
                elif count == 2:
                    matrix[x * 3][y] = cards[x][y].pop(0)
                    matrix[x * 3 + 2][y] = cards[x][y].pop(0)
                    row1 += 1
                    row3 += 1
                    count = 0

        # third and last iteration with all the singles
        for y in range(0, len(cards[x])):
            if len(cards[x][y]) == 1:
                if row1 <= row2 and row1 <= row3:
                    matrix[x * 3][y] = cards[x][y].pop(0)
                    row1 += 1
                elif row2 <= row1 and row2 <= row3:
                    matrix[x * 3 + 1][y] = cards[x][y].pop(0)
                    row2 += 1
                elif row3 <= row1 and row3 <= row2:
                    matrix[x * 3 + 2][y] = cards[x][y].pop(0)
                    row3 += 1

    card_string(matrix)

if __name__ == '__main__':
    main(sys.argv[1:])
