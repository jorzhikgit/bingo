<?php

$latex = <<<'EOD'
\documentclass[english]{article}
\usepackage[paperwidth=100mm,paperheight=210mm,margin=0mm,top=10mm]{geometry}
\usepackage{array}
\usepackage{verbatimbox}
\usepackage{titlesec}
\usepackage[absolute]{textpos}
\usepackage{graphicx}
\usepackage{newunicodechar}

\setlength{\TPHorizModule}{\paperwidth}\setlength{\TPVertModule}{\paperheight}

\titlespacing*{\section}{10mm}{10mm}{10mm}

\renewcommand{\familydefault}{\sfdefault}
\newunicodechar{æ}{\ae}
\newunicodechar{Æ}{\AE}
\newunicodechar{ø}{\o}
\newunicodechar{Ø}{\O}
\newunicodechar{å}{\aa}
\newunicodechar{Å}{\AA}
\newunicodechar{à}{\`a}

\begin{document}
  {\Large \tt{S-%d}}
  \begin{center}
    \addvbuffer[0mm 5mm]{\begin{tabular}{|>{\centering\arraybackslash}m{90mm}|}
        \hline
          \addvbuffer[1mm 2mm]{\includegraphics[width=70mm]{logo.png}} \\
          \addvbuffer[0mm 2mm]{{\textbf{\huge Nærkanalen}}} \\
          \addvbuffer[0mm 2mm]{{\textbf{\Large 100,6 - 107,1 - 106,7 - 104,9 MHz}}} \\
          \addvbuffer[0mm 2mm]{Gammelveien 1, 8150 Ørnes} \\
          \addvbuffer[0mm 2mm]{{\textbf{\Huge Tlf. 75 72 05 50}}} \\
          Tipstelefon 97 96 20 00 - Fax 75 72 05 60 \\
      \hline
    \end{tabular}}
    \addvbuffer[0mm 5mm]{\begin{tabular}{>{\centering\arraybackslash}m{90mm}}
          \\
            {\normalsize
            \begin{enumerate}
              \item Det kan kun benyttes godkjente blokker og spill, og de skal være merket med ukenummer, og/eller kommisjonær\-stempel.
              \item Det spilles om 1, 2 og 3-rader.
              \item Ved oppnådd bingo ringes det snarest mulig til Nærkanalen, tlf. 75 72 05 50.
              \item Premier utbetales påfølgende dag hos våre kommisjonærer, mot innlevering og kontroll av vinnerblokk.
              \item Kun hele blokker godtas!
              \item Hvis flere spillere oppnår bingo på identisk kontroll\-nummer, ubetales identisk premie. Ved bingo på forskjellig kontrollnummer, deles premien, dog med en minste utbetaling à kr. 100,-.
              \item Det spilles fortløpende om en lykkepott på maks kr. 20.000,-. (Dette gjelder ikke pausespill.)
              \item \textbf{OBS!!} Hvis lykkepotten går til flere spillere med identisk kontrollnummer, må denne deles. Ved flere vinnere med forskjellig kontrollnummer, deles potten etter vanlige regler.
            \end{enumerate}}
          \\
    \end{tabular}}
  \end{center}
\newpage
EOD;

?>