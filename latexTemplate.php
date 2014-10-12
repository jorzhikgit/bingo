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
          \\
          \addvbuffer[0mm 0mm]{{\textbf{\huge Bingo}}} \\
          \\
      \hline
    \end{tabular}}
    \addvbuffer[0mm 5mm]{\begin{tabular}{>{\centering\arraybackslash}m{90mm}}
          \\
            {\normalsize
            \begin{enumerate}
              \item Gameplay: Line, two lines and full house.
              \item Only full booklets are accepted!
              \item If multiple winners have the same verification code, the price will be paid in full to all involved. If the verification code is different, the price is split.
            \end{enumerate}}
          \\
    \end{tabular}}
  \end{center}
EOD;

?>