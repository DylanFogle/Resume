from tkinter import Tk, Button, Entry, Radiobutton, Label, IntVar, NORMAL, DISABLED, W, E
from math import sin, cos, tan, pi, e, sqrt, degrees, radians

# Set up root.
root = Tk()
root.title("Dylan's Calculator")
root.resizable(False, False)

# Define global variables.
g_Equation = ""
g_Output = ""
g_EHistory = []
g_OHistory = []
g_leftOperand = ""
g_rightOperand = ""
g_leftOpFlag = True
g_Operator = ""
g_Mode = IntVar()

# Define functions for use.
def updateDisplay(char):
    global g_leftOpFlag
    global g_leftOperand
    global g_rightOperand
    global g_Operator
    global g_EHistory

    if not char.isalnum() and char != "." and g_leftOperand:
        g_leftOpFlag = False
        g_Operator = char
    elif (char.isalnum() or char==".") and g_leftOpFlag:
        g_leftOperand = g_leftOperand + char
    elif (char.isalnum() or char==".") and not g_leftOpFlag:
        g_rightOperand = g_rightOperand + char

    if g_leftOperand.count(".") > 1:
        g_leftOperand = g_leftOperand[:-1]
    if g_rightOperand.count(".") > 1:
        g_rightOperand = g_rightOperand[:-1]

    if g_leftOperand:
        labelEquation.configure(text=g_leftOperand+g_Operator+g_rightOperand)

    radioDegree.select()
    radioDegree.configure(state=DISABLED)
    radioRadian.configure(state=NORMAL)


def clearDisplay():
    global g_leftOpFlag
    g_leftOpFlag = True
    global g_leftOperand
    g_leftOperand = ""
    global g_rightOperand
    g_rightOperand = ""
    global g_Operator
    g_Operator = ""
    labelEquation.configure(text="")
    labelOutput.configure(text="")
    radioDegree.select()
    radioDegree.configure(state=DISABLED)
    radioRadian.configure(state=NORMAL)

def finishDisplay(OpCheck):
    global g_leftOpFlag
    g_leftOpFlag = True
    global g_leftOperand
    global g_rightOperand
    global g_Operator
    global g_OHistory
    global g_EHistory

    if g_leftOperand == ".":
        g_leftOperand = "0"
    if g_rightOperand == ".":
        g_rightOperand = "0"

    if not g_rightOperand:
        g_rightOperand = "1"

    for index, key in enumerate(str(g_leftOperand)):
        if key == "π":
            g_leftOperand = g_leftOperand.replace("π", "", 1)
            if g_leftOperand:
                g_leftOperand = float(g_leftOperand) * pi
            else:
                g_leftOperand = pi
        elif key == "e":
            g_leftOperand = g_leftOperand.replace("e", "", 1)
            if g_leftOperand:
                g_leftOperand = float(g_leftOperand) * e
            else:
                g_leftOperand = e

    for index,key in enumerate(str(g_rightOperand)):
        if key == "π":
            g_rightOperand = g_rightOperand.replace("π", "", 1)
            if g_rightOperand:
                g_rightOperand = float(g_rightOperand) * pi
            else:
                g_rightOperand = pi
        elif key == "e":
            g_rightOperand = g_rightOperand.replace("e", "", 1)
            if g_rightOperand:
                g_rightOperand = float(g_rightOperand) * e
            else:
                g_rightOperand = e


    if g_Operator == "+":
        ans = float(g_leftOperand) + float(g_rightOperand)
    elif g_Operator == "-":
        ans = float(g_leftOperand) - float(g_rightOperand)
    elif g_Operator == "*":
        ans = float(g_leftOperand) * float(g_rightOperand)
    elif g_Operator == "/":
        if int(g_rightOperand) == 0:
            ans = ""
        else:
            ans = float(g_leftOperand) / float(g_rightOperand)
    elif g_Operator == "^":
        ans = float(g_leftOperand)**float(g_rightOperand)
    elif g_Operator == "%":
        ans = float(g_leftOperand)%float(g_rightOperand)
    else:
        ans = g_leftOperand

    labelOutput.configure(text=str(ans))
    g_OHistory.append(str(ans))
    if OpCheck:
        g_EHistory.append(str(g_leftOperand)+str(g_Operator)+str(g_rightOperand))
    g_leftOperand = str(ans)
    g_rightOperand = ""
    g_Operator = ""
    showHistory()

def actOnNumber(action):
    global g_leftOperand
    global g_EHistory
    trig = ("sin","cos","tan")

    for index, key in enumerate(str(g_leftOperand)):
        if key == "π":
            g_leftOperand = g_leftOperand.replace("π", "", 1)
            if g_leftOperand:
                g_leftOperand = float(g_leftOperand) * pi
            else:
                g_leftOperand = pi
        elif key == "e":
            g_leftOperand = g_leftOperand.replace("e", "", 1)
            if g_leftOperand:
                g_leftOperand = float(g_leftOperand) * e
            else:
                g_leftOperand = e

    if g_leftOperand and g_leftOperand != ".":
        if action in trig:
            radioRadian.select()
            radioDegree.configure(state=NORMAL)
            radioRadian.configure(state=DISABLED)

        if action == "flip":
            g_leftOperand = float(g_leftOperand) * -1.0
            labelEquation.configure(text=str(g_leftOperand))
        elif action == "sin":
            labelEquation.configure(text="sin(" + str(g_leftOperand) + ")")
            g_EHistory.append("sin(" + str(g_leftOperand) + ")")
            g_leftOperand = round(sin(float(g_leftOperand)), 6)
        elif action == "cos":
            labelEquation.configure(text="cos(" + str(g_leftOperand) + ")")
            g_EHistory.append("cos(" + str(g_leftOperand) + ")")
            g_leftOperand = round(cos(float(g_leftOperand)), 6)
        elif action == "tan":
            labelEquation.configure(text="tan(" + str(g_leftOperand) + ")")
            g_EHistory.append("tan(" + str(g_leftOperand) + ")")
            g_leftOperand = round(tan(float(g_leftOperand)), 6)
        elif action == "sqrt":
            labelEquation.configure(text="sqrt(" + str(g_leftOperand) + ")")
            g_EHistory.append("sqrt(" + str(g_leftOperand) + ")")
            g_leftOperand = sqrt(float(g_leftOperand))
        elif action == "inv":
            labelEquation.configure(text="1/" + str(g_leftOperand))
            g_EHistory.append("1/" + str(g_leftOperand))
            g_leftOperand = 1/float(g_leftOperand)
    finishDisplay(False)

def changeMode():
    global g_leftOperand
    global g_OHistory
    if g_leftOperand and g_leftOperand != ".":
        if g_Mode.get() == 1:
            radioDegree.configure(state=DISABLED)
            radioRadian.configure(state=NORMAL)
            g_leftOperand = str(degrees(float(g_leftOperand)))
        elif g_Mode.get() == 2:
            radioDegree.configure(state=NORMAL)
            radioRadian.configure(state=DISABLED)
            g_leftOperand = str(radians(float(g_leftOperand)))
    else:
        radioDegree.select()
        radioDegree.configure(state=DISABLED)
        radioRadian.configure(state=NORMAL)
    labelOutput.configure(text=g_leftOperand)

def roundDisplay():
    global g_leftOperand
    g_leftOperand = round(float(g_leftOperand), int(entryRound.get()))
    labelOutput.configure(text=g_leftOperand)

def showHistory():
    global g_OHistory
    global g_EHistory
    textHistory = ""
    labelHistory = Label(root, text=textHistory, height=23, width=15)
    for OHist, EHist in zip(g_OHistory, g_EHistory):
        textHistory = textHistory + "\n" + EHist + " = " + OHist

    labelHistory.configure(text=textHistory)
    labelHistory.grid(row=1, column=6, rowspan=6)
    buttonCHistory.grid(row=0, column=6)

def clearHistory():
    global g_OHistory
    global g_EHistory
    g_OHistory.clear()
    g_EHistory.clear()
    showHistory()

# Define text displays.
labelEquation = Label(root, text="")
labelOutput = Label(root, text="")

# Define radio buttons.
radioDegree = Radiobutton(root, text="Degree", variable=g_Mode, value=1, command=changeMode)
radioDegree.select()
radioRadian = Radiobutton(root, text="Radian", variable=g_Mode, value=2, command=changeMode)

# Define entry area.
entryRound = Entry(root, width=2)

# Define numeric buttons.
button1 = Button(root, text="1", command=lambda: updateDisplay("1"))
button2 = Button(root, text="2", command=lambda: updateDisplay("2"))
button3 = Button(root, text="3", command=lambda: updateDisplay("3"))
button4 = Button(root, text="4", command=lambda: updateDisplay("4"))
button5 = Button(root, text="5", command=lambda: updateDisplay("5"))
button6 = Button(root, text="6", command=lambda: updateDisplay("6"))
button7 = Button(root, text="7", command=lambda: updateDisplay("7"))
button8 = Button(root, text="8", command=lambda: updateDisplay("8"))
button9 = Button(root, text="9", command=lambda: updateDisplay("9"))
button0 = Button(root, text="0", command=lambda: updateDisplay("0"))
buttonD = Button(root, text=".", command=lambda: updateDisplay("."))
buttonPi = Button(root, text="π", command=lambda: updateDisplay("π"))
buttonE = Button(root, text="e", command=lambda: updateDisplay("e"))

# Define action buttons.
buttonClear = Button(root, text="C", command=clearDisplay)
buttonEqual = Button(root, text="=", command=lambda: finishDisplay(True))
buttonFlip = Button(root, text="+/-", command=lambda: actOnNumber("flip"))
buttonAdd = Button(root, text="+", command=lambda: updateDisplay("+"))
buttonSub = Button(root, text="-", command=lambda: updateDisplay("-"))
buttonMul = Button(root, text="*", command=lambda: updateDisplay("*"))
buttonDiv = Button(root, text="/", command=lambda: updateDisplay("/"))
buttonSin = Button(root, text="sin", command=lambda: actOnNumber("sin"))
buttonCos = Button(root, text="cos", command=lambda: actOnNumber("cos"))
buttonTan = Button(root, text="tan", command=lambda: actOnNumber("tan"))
buttonSqrt = Button(root, text="sqrt", command=lambda: actOnNumber("sqrt"))
buttonExp = Button(root, text="^", command=lambda: updateDisplay("^"))
buttonInv = Button(root, text="1/x", command=lambda: actOnNumber("inv"))
buttonMod = Button(root, text="%", command=lambda: updateDisplay("%"))
buttonRound = Button(root, text="Round", command=roundDisplay)
buttonCHistory = Button(root, text="Clear History", command=clearHistory)

# Lay out labels on screen.
labelEquation.grid(row=0, column=1, columnspan=2)
labelOutput.grid(row=1, column=1, columnspan=2)

# Lay out radio buttons on screen.
radioDegree.grid(row=0, column=0)
radioRadian.grid(row=1, column=0)

# Lay out entry and round widgets.
entryRound.grid(row=0, column=5)
buttonRound.grid(row=1, column=5)

# Lay out buttons on screen.
button1.grid(row=4, column=0, ipadx=31, ipady=30)
button2.grid(row=4, column=1, ipadx=31, ipady=30)
button3.grid(row=4, column=2, ipadx=31, ipady=30)
button4.grid(row=3, column=0, ipadx=31, ipady=30)
button5.grid(row=3, column=1, ipadx=31, ipady=30)
button6.grid(row=3, column=2, ipadx=31, ipady=30)
button7.grid(row=2, column=0, ipadx=31, ipady=30)
button8.grid(row=2, column=1, ipadx=31, ipady=30)
button9.grid(row=2, column=2, ipadx=31, ipady=30)
button0.grid(row=5, column=1, ipadx=31, ipady=30)
buttonClear.grid(row=5, column=0, ipadx=15, ipady=30, sticky=W)
buttonEqual.grid(row=5, column=0, ipadx=10, ipady=30, sticky=E)
buttonD.grid(row=5, column=2, ipadx=14, ipady=30, sticky=W)
buttonFlip.grid(row=5, column=2, ipadx=5, ipady=30, sticky=E)
buttonAdd.grid(row=2, column=3, ipadx=5, ipady=5)
buttonSub.grid(row=3, column=3, ipadx=5, ipady=5)
buttonMul.grid(row=4, column=3, ipadx=5, ipady=5)
buttonDiv.grid(row=5, column=3, ipadx=5, ipady=5)
buttonSin.grid(row=2, column=4, ipadx=5, ipady=5)
buttonCos.grid(row=3, column=4, ipadx=5, ipady=5)
buttonTan.grid(row=4, column=4, ipadx=5, ipady=5)
buttonSqrt.grid(row=5, column=4, ipadx=5, ipady=5)
buttonExp.grid(row=2, column=5, ipadx=5, ipady=5)
buttonInv.grid(row=3, column=5, ipadx=5, ipady=5)
buttonPi.grid(row=4, column=5, ipadx=1, ipady=5, sticky=W)
buttonE.grid(row=4, column=5, ipadx=1, ipady=5, sticky=E)
buttonMod.grid(row=5, column=5, ipadx=5, ipady=5)

# Run the main loop of the program.
root.mainloop()
