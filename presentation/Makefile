OUT := presentation


$(OUT).pdf: $(OUT).tex
	pdflatex $^
	pdflatex $^


.PHONY: clean
clean:
	rm -f *.aux *.dvi *.log *.ps  *.fls *.synctex.gz *.fls *.fdb_latexmk *.nav \
		*.out *.snm *.toc *.vrb *.toc
