import pygame, sys, os, random
from pygame.locals import *

SCREENX = 600
SCREENY = 600

pygame.init()
pygame.display.set_caption("Dylan's Blackjack")

clock = pygame.time.Clock()
screen = pygame.display.set_mode((SCREENX,SCREENY))
random.seed()
font = pygame.font.Font(pygame.font.get_default_font(), int(SCREENX / 40))
gameState = "START"

class deck:
    # A deck is composed of 52 cards.
    def __init__(self):
        self.list = []
        for x in range(52):
            self.list.append(card(x+1))

    def draw_card(self, hand):
        num = random.randrange(0, len(self.list))
        hand.append(self.list[num])
        del self.list[num]      

class card:
    # A card has a value, suit, and image.
    def __init__(self, id):
        self.id = id
        self.value = id % 13
        if self.value == 0: self.value = 10 # King
        if self.value == 12: self.value = 10 # Queen
        if self.value == 11: self.value = 10 # Jack
        self.set_suit()
        self.set_image()
    
    def set_suit(self):
        if self.id < 14:
            self.suit = "Club"
        elif self.id < 27:
            self.suit = "Diamond"
        elif self.id < 40:
            self.suit = "Heart"
        else:
            self.suit = "Spade"

    def set_image(self):
        self.num = self.id % 13
        if self.num == 0: self.num= 13 # King
        if self.suit == "Club":
            self.image = pygame.image.load(os.path.join("textures/Clubs","Clubs"+str(self.num)+".png"))
        elif self.suit == "Diamond":
            self.image = pygame.image.load(os.path.join("textures/Diamonds","Diamonds"+str(self.num)+".png"))
        elif self.suit == "Heart":
            self.image = pygame.image.load(os.path.join("textures/Hearts","Hearts"+str(self.num)+".png"))
        else:
            self.image = pygame.image.load(os.path.join("textures/Spades","Spades"+str(self.num)+".png"))

    def set_back(self):
        self.image = pygame.image.load(os.path.join("textures","BackBlue1.png"))

class main:
    # Main class to call functions and handle game logic.
    def __init__(self):
        self.playerHand = []
        self.dealerHand = []
        self.deck = deck()
        self.hitButton = pygame.image.load(os.path.join("textures","Hit.png"))
        self.hitRect = self.hitButton.get_rect(center = (SCREENX / 2 - SCREENX * .2,SCREENY / 2))
        self.stayButton = pygame.image.load(os.path.join("textures","Stay.png"))
        self.stayRect = self.stayButton.get_rect(center = (SCREENX / 2 + SCREENX * .2,SCREENY / 2))
        self.text = ""
        self.start_game()

    def draw_dialogue(self):
        screen.blit(self.hitButton,self.hitRect)
        screen.blit(self.stayButton,self.stayRect)

    def draw_log(self, message):
        self.textSurface = font.render(message, False, (0,0,0))
        screen.blit(self.textSurface, (SCREENX * .05, SCREENY * 5 / 6))

    def start_game(self):
        self.deck.draw_card(self.playerHand)
        self.deck.draw_card(self.playerHand)
        self.deck.draw_card(self.dealerHand)
        self.deck.draw_card(self.dealerHand)

    def draw_hands(self):
        global gameState
        for x in range(len(self.playerHand)):
            screen.blit(self.playerHand[x].image, (SCREENX / 2.5 + x * (SCREENX / 10), SCREENY / 4 * 3))
        for x in range(len(self.dealerHand)):
            if gameState == "STAYED": self.dealerHand[x].set_image()
            else: self.dealerHand[x].set_back()
            screen.blit(self.dealerHand[x].image, (SCREENX / 2.5 + x * (SCREENX / 10), SCREENY / 8))

    def check_for_finish(self):
        global gameState
        self.playerCount = 0
        self.dealerCount = 0
        if gameState == "START":
            for x in range(len(self.playerHand)):
                self.playerCount += self.playerHand[x].value
                if self.playerHand[x].value == 1: self.playerCount += 10
                if self.playerCount > 21 and 1 in self.playerHand:
                    self.playerCount -= 10
                if self.playerCount > 21:
                    self.draw_log("You busted, dealer wins!")
                    gameState = "FINISHED"
                    return
            for x in range(len(self.dealerHand)):
                self.dealerCount += self.dealerHand[x].value
                if self.dealerHand[x].value == 1: self.dealerCount += 10
                if self.dealerCount > 21 and 1 in self.dealerHand:
                    self.dealerCount -= 10
                if self.dealerCount > 21:
                    self.draw_log("Dealer busted, you win!")
                    gameState = "FINISHED"
                    return
        elif gameState == "STAYED":
            for x in range(len(self.playerHand)):
                self.playerCount += self.playerHand[x].value
                if self.playerHand[x].value == 1: self.playerCount += 10
                if self.playerCount > 21 and 1 in self.playerHand:
                    self.playerCount -= 10
                if self.playerCount > 21:
                    self.draw_log("You busted, dealer wins!")
                    gameState = "FINISHED"
                    return
            for x in range(len(self.dealerHand)):
                self.dealerCount += self.dealerHand[x].value
                if self.dealerHand[x].value == 1: self.dealerCount += 10
                if self.dealerCount > 21 and 1 in self.dealerHand:
                    self.dealerCount -= 10
                if self.dealerCount > 21:
                    self.draw_log("Dealer busted, you win!")
                    gameState = "FINISHED"
                    return
            if self.playerCount > self.dealerCount:
                self.draw_log("You win! "+str(self.playerCount)+" to "+str(self.dealerCount))
                gameState = "FINISHED"
            elif self.playerCount < self.dealerCount:
                self.draw_log("You lose! "+str(self.playerCount)+" to "+str(self.dealerCount))
                gameState = "FINISHED"
            else:
                self.draw_log("You tie!"+str(self.playerCount)+" to "+str(self.dealerCount))
                gameState = "FINISHED"

game = main()

while True:
    pygame.display.update()
    screen.fill((51,60,36))
    clock.tick(60)

    if gameState == "START":
        game.draw_hands()
        game.draw_dialogue()
        game.check_for_finish()
    elif gameState == "STAYED":
        dealerCount = 0
        for x in range(len(game.dealerHand)):
                game.dealerCount += game.dealerHand[x].value
                if game.dealerHand[x].value == 1: game.dealerCount += 10
                if game.dealerCount > 21 and 1 in game.dealerHand:
                    game.dealerCount -= 10
        if dealerCount < 17: game.deck.draw_card(game.dealerHand)
        game.draw_hands()
        game.check_for_finish()
    elif gameState == "FINISHED":
        game.draw_hands()
        pygame.time.wait(1200)
        gameState = "START"
        game.__init__()

    for event in pygame.event.get():
        if event.type == QUIT:
            pygame.quit()
            sys.exit()
        if event.type == pygame.MOUSEBUTTONDOWN and event.button == 1:
            if game.hitRect.collidepoint(pygame.mouse.get_pos()):
                game.deck.draw_card(game.playerHand)
            elif game.stayRect.collidepoint(pygame.mouse.get_pos()):
                gameState = "STAYED"
    

