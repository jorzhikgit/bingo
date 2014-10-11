#!/usr/bin/env python

import sys
import random

rows = 18
cols = 9

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
    
    numbers = [range(1,10),
               range(10,20),
               range(20,30),
               range(30,40),
               range(40,50),
               range(50,60),
               range(60,70),
               range(70,80),
               range(80,91)]

    for x in numbers:
        random.shuffle(x)

    # give at least one number to each column in each card
    for x in range(0, len(cards)):
        for y in range(0, len(numbers)):
            number = numbers[y].pop()
            try:
                cards[x][y].append(number)
            except IndexError:
                cards[x] = []
                cards[x][y].append(number)

    # one of the cards get the extra number
    cards[random.randint(0, len(cards) - 1)][8].append(numbers[8].pop())

    for x in range(0, 4):
        for y in range(0, len(numbers)):
            try:
                number = numbers[y].pop()

                choices = range(0, len(cards))

                while 1:
                    # pick a random card
                    card = random.choice(choices)

                    # if we have available slots in the card
                    if get_items_in_card(cards[card]) == 15:
                        choices.remove(card)
                        continue
                    
                    if len(cards[card][y]) == 2 and x < 3:
                        choices.remove(card)
                        continue
                    
                    if len(cards[card][y]) == 3 and x == 3:
                        choices.remove(card)
                        continue

                    cards[card][y].append(number)
                    break
                    
            except IndexError:
                continue

    for card in cards:
        for y in range(0, len(card)):
            card[y] = sorted(card[y])

    for x in range(0, len(cards)):
        row1 = row2 = row3 = 0
        for y in range(0, len(cards[x])):
            # we have no choice but to fill this column in this card
            if len(cards[x][y]) == 3:
                matrix[x * 3][y]     = cards[x][y][0]
                matrix[x * 3 + 1][y] = cards[x][y][1]
                matrix[x * 3 + 2][y] = cards[x][y][2]
                del cards[x][y][:]
                row1 += 1
                row2 += 1
                row3 += 1

        count = 0

        # make the order in which we spread the 2-column a bit more random
        for y in range(0, len(cards[x])):
            if len(cards[x][y]) == 2:
                if count == 0:
                    matrix[x * 3][y] = cards[x][y][0]
                    matrix[x * 3 + 1][y] = cards[x][y][1]
                    cards[x][y].remove(matrix[x * 3][y])
                    cards[x][y].remove(matrix[x * 3 + 1][y])
                    row1 += 1
                    row2 += 1
                    count = 1
                elif count == 1:
                    matrix[x * 3 + 1][y] = cards[x][y][0]
                    matrix[x * 3 + 2][y] = cards[x][y][1]
                    cards[x][y].remove(matrix[x * 3 + 1][y])
                    cards[x][y].remove(matrix[x * 3 + 2][y])
                    row2 += 1
                    row3 += 1
                    count = 2
                elif count == 2:
                    matrix[x * 3][y] = cards[x][y][0]
                    matrix[x * 3 + 2][y] = cards[x][y][1]
                    cards[x][y].remove(matrix[x * 3][y])
                    cards[x][y].remove(matrix[x * 3 + 2][y])
                    row1 += 1
                    row3 += 1
                    count = 0

        for y in range(0, len(cards[x])):
            if len(cards[x][y]) == 1:
                if row1 <= row2 and row1 <= row3:
                    matrix[x * 3][y] = cards[x][y][0]
                    del cards[x][y][:]
                    row1 += 1
                elif row2 <= row1 and row2 <= row3:
                    matrix[x * 3 + 1][y] = cards[x][y][0]
                    del cards[x][y][:]
                    row2 += 1
                elif row3 <= row1 and row3 <= row2:
                    matrix[x * 3 + 2][y] = cards[x][y][0]
                    del cards[x][y][:]
                    row3 += 1

    output_card(matrix)

if __name__ == '__main__':
    main(sys.argv[1:])
