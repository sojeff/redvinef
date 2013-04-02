<?php
/*
    TextFit.class.php                        v1.1.3    2012-12-05
    ---------------------------------------------------------------------
    
    TextFit is a simple PHP class that shortens a string, 
    or fits an array of plain text strings into columns.
    
    http://tweezy.net.au/TextFit.html
    
    For more info see TextFit.doc.txt and examples.php
    
    Copyright (C) 2012 Tony Phelps
    ---------------------------------------------------------------------
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    ---------------------------------------------------------------------
    email: dev@tweezy.net.au
    post:  P O Box 200 Kingston Tas 7051 AUSTRALIA
    ---------------------------------------------------------------------
*/

class TextFit {

private $Columns;      // array of column attributes
private $EOL;          // appended to fitTextRow and fitTextLine
private $DefaultWidth; // width used by fitTextLine and fitTextRow if unspecified for a column
private $PadChar;      // padding between columns; default is space
private $WrapText;     // if true, long text is wrapped within each column, not shortened
private $MaxLines;     // maximum wrap lines
private $AddExtraRow;  // if true, a blank line will be added after each fitTextRow. If a string, fitTextLine will be added using that string
private $Indent='';    // spaces prepended to each row, effectively indenting the "table"
private $EchoIt=false;
private $AddingBlank=false; // safety switch to stop fitTextBlank and fitTextRow calling each other forever
private $RowStart;  private $RowSep;  private $RowEnd;
private $LineStart; private $LineSep; private $LineEnd;
    
    
public function  __construct($Columns=null, $WrapText=null, $AddExtraRow=null)
    {
    $this->setDefaultWidth(null); // must be called before setColumns
    $this->setColumns($Columns);
    $this->setRowChar();
    $this->setLineChar();
    $this->setEOL(null);
    $this->setPadChar(null);
    $this->setWrap($WrapText);
    $this->setAddRow($AddExtraRow);
    }


public function fitTextRow($RowValues, $PadChar=null)
    {
    /*
    Returns or prints a string with text values spaced according to preset columns
    
    $RowValues .. an array of text values (one for each column)
    $PadChar .... can be set here as a one-off. Normally the default is used, which can be 
                    set globally on instantiation or with ->setPadChar()
    */
    
    $ftrOutput='';
    $String=array_values($RowValues);// array_values ensures numeric indexes starting at zero, matching $this->Columns indexes
    if(is_null($PadChar)) { $PadChar=$this->PadChar; }
    $AddAnotherLine=true;
    $LinesAdded=array_fill(0, count($String), 0);
    
    while($AddAnotherLine)
        {
        $ftrOutput.=$this->Indent;
        
        if($this->RowStart != '')
            { $ftrOutput.=$this->RowStart.$PadChar; }
        elseif($this->LineStart != '')
            { $ftrOutput.=$PadChar.$PadChar; }
        
        $AddAnotherLine=false;
        
        foreach($String as $RowId=>$ThisString)
            {
            if(!isset($this->Columns[$RowId]))
                { $this->Columns[$RowId]=$this->_fixColumn($this->DefaultWidth); }
            
            // Remove newline characters (can break column layout)..
            $ThisString=str_replace(array("\r\n","\r","\n"), '', $ThisString);
            $ThisString=strip_tags($ThisString);
            // ..shortenText also calls strip_tags, but we need to do it here because 
            //   html tags mess up the column width and/or get broken by shortening
            
            // column width..
            $Width=$this->Columns[$RowId]['width'];
            
            $String[$RowId]=''; // empty this string (assume single line for now)
            $LinesAdded[$RowId]++; // well we nearly have!
            
            if(strlen($ThisString)>$Width) // need to shorten or wrap
                {
                $ValueToPrint=$this->shortenText($ThisString, $Width-2); // must add '...'
                
                // If we can't break on a word, force cutoff..
                if('...'==$ValueToPrint)
                    { $ValueToPrint=$this->shortenText($ThisString, $Width-3, true); } // must add '...'

                if($this->WrapText and ($this->MaxLines<=0 or $LinesAdded[$RowId]<$this->MaxLines))
                    {
                    // remove '...' from end..
                    $ValueToPrint=substr($ValueToPrint, 0, -3);
                    // keep the remainder for next line..
                    $String[$RowId]=trim(substr($ThisString, strlen($ValueToPrint)));
                    // loop again..
                    $AddAnotherLine=true;
                    }

                }
            else
                { $ValueToPrint=$ThisString; }
            
            
            // set case..
            switch(strtoupper(substr($this->Columns[$RowId]['case'],0,1)))
                {
                case 'U': $ValueToPrint=strtoupper($ValueToPrint); break;
                case 'L': $ValueToPrint=strtolower($ValueToPrint); break;
                case 'P': $ValueToPrint=ucwords(strtolower($ValueToPrint)); break;
                default: //nop
                }
            
            $PadType=$this->Columns[$RowId]['padtype'];
            
            $ftrOutput.=str_pad($ValueToPrint, $Width, $PadChar, $PadType);
            
            if($RowId < count($String)-1) // not last column
                {
                if(''==$this->RowSep)
                    { $ftrOutput.=(''==$this->LineSep) ? $PadChar : $PadChar.' '.$PadChar; }
                else
                    { $ftrOutput.=$PadChar.$this->RowSep.$PadChar; }
                
                }
            }
        
        if($this->RowEnd != '') $ftrOutput.=$PadChar;
        $ftrOutput.=$this->RowEnd;
        $ftrOutput.=$this->EOL;
        }
    
    unset($LinesAdded);
    
    if($this->AddExtraRow)
        {
        if(is_string($this->AddExtraRow))
            { $ftrOutput.=$this->fitTextLine($this->AddExtraRow); }
        elseif(!$this->AddingBlank)
            { $ftrOutput.=$this->fitTextBlank($PadChar); }
        }
        
   if($this->EchoIt)
        { echo $ftrOutput; }
    else
        { return $ftrOutput; }
    
    }

public function fitTextList($List, $FillDown=true, $AutoWidth=false)
    {
    /*
        Returns or prints multiple rows from a simple array of strings
        
        $List ....... A simple array of strings
        $FillDown ... If true, values are listed down the first column, then the second, etc.
                      If false, values are listed row by row
        $AutoWidth .. If true, the widths set with setColumns() are ignored, and all columns 
                      will be the size of the longest string in $List (no wrapping!).
                      Any column 'align' and/or 'case' settings will still be used.
                      $AutoWidth can also be an integer specifying the number of columns 
                      to use; in this case you don't need to call setColumns() at all 
                      (and if you do, it will be ignored).
    */
    
    $NumValues=count($List);
    $NumCols=count($this->Columns);
    $List=array_values($List); // ensures 0-based numeric indexes
    
    if($AutoWidth)
        {
        //save global columns..
        $SavedColumns=$this->Columns;
        
        //find longest string..
        $Longest=0;
        foreach($List as $ThisValue)
            { if(strlen($ThisValue)>$Longest) { $Longest=strlen($ThisValue); } }
        
        //set widths..
        if(is_int($AutoWidth) and $AutoWidth>0)
            {
            $NumCols=$AutoWidth;
            $this->setColumns(array_fill(0, $NumCols, $Longest));
            }
        else
            {
            foreach($this->Columns as $ColId=>$ThisCol)
                { $this->Columns[$ColId]['width']=$Longest; }
            }
        }
    
    $ftlOutput='';
    
    if($FillDown) // fill down
        {
        $NumRows=ceil($NumValues/$NumCols);
        for($row=0; $row<$NumRows; $row++)
            {
            $RowValues=array_fill(0, $NumCols, '');
            
            for($col=0; $col<$NumCols; $col++)
                {
                if(isset($List[$row+$col*$NumRows]))
                    { $RowValues[$col]=$List[$row+$col*$NumRows]; }
                else
                    { break; }
                }
            $ftlOutput.=$this->fitTextRow($RowValues);
            }
        }
    else // fill across
        {
        for($i=0; $i<$NumValues; $i+=$NumCols)
            {
            $RowValues=array_fill(0, $NumCols, '');
            for($col=0; $col<$NumCols; $col++)
                {
                if(isset($List[$col+$i]))
                    { $RowValues[$col]=$List[$col+$i]; }
                else
                    { break; }
                }
            $ftlOutput.=$this->fitTextRow($RowValues);
            }
        }
    
    // restore global columns..
    if($AutoWidth)
        { $this->Columns=$SavedColumns; }
    
    if($this->EchoIt)
        { echo $ftlOutput; }
    else
        { return $ftlOutput; }
    }

public function fitTextBlank($PadChar=null)
    {
    // Returns or prints a blank row
    // This is the same as calling fitTextRow with empty values
    
    $this->AddingBlank=true;
    $Result=$this->fitTextRow(array_fill(0, count($this->Columns), ''), $PadChar);
    $this->AddingBlank=false;
    return $Result;
    }

public function fitTextLine($Char='-', $ColSpan=null)
    {
    // Returns or prints a string the width of a row of 
    // preset columns, using the $Char character
    
    // If $ColSpan>0, only span that number of columns
    
    $ColSpan=(int)$ColSpan;
    // restrict to 1 char..
    $Char=(is_string($Char) and strlen($Char)>0) ? $Char{0} : '-';
    
    $TheResult=$this->Indent;
    
    if($this->LineStart != '')
        { $TheResult.=$this->LineStart.$Char; }
    elseif($this->RowStart != '')
        { $TheResult.=$Char.$Char; }
    
    foreach($this->Columns as $ColId=>$ThisCol)
        {
        if($ColSpan>0 and $ColId>=$ColSpan) break;
        $TheResult.=str_repeat($Char, $ThisCol['width']);
        if($ColId < count($this->Columns)-1) // not last column
            {
            if(''==$this->LineSep)
                { $TheResult.=(''==$this->RowSep) ? $Char : $Char.$Char.$Char; }
            else
                { $TheResult.=$Char.$this->LineSep.$Char; }
            
            }
        }
    
    if($this->RowEnd != '') $TheResult.=$Char;

    if($this->LineEnd != '') $TheResult.=$this->LineEnd;
    elseif($this->RowEnd != '') $TheResult.=$Char;
    
    $TheResult.=$this->EOL;
    
    if($this->EchoIt)
        { echo $TheResult; }
    else
        { return $TheResult; }
    
    }

public function fitTextRows($RowValues, $AddHeading=false, $LineChar=true)
    {
    /*
        Returns or prints multiple rows from an array
        
        $RowValues .... multi-row array
        $AddHeading ... if true, a heading row is generated from the subarray indexes 
        $LineChar ..... 2-char string indicating line above+below heading
                        1-char string for same line above+below, or true = '=', false = 'N'
    */
    
    $TheOutput='';
    
    if($AddHeading)
        {

        $LineAbove=false;
        $LineBelow=false;
        
        // make $LineChar a string..
        if(!is_string($LineChar)) { $LineChar=($LineChar) ? '=' : 'N'; }
        // make $LineChar 2 chars..
        if(0==strlen($LineChar)) { $LineChar='NN'; }
        elseif(1==strlen($LineChar)) { $LineChar.=$LineChar; }
        // get $LineAbove, $LineBelow from $LineChar..
        if($LineChar{0} != 'N') { $LineAbove=$LineChar{0}; }
        if($LineChar{1} != 'N') { $LineBelow=$LineChar{1}; $SavedExtra=false; }
        if($LineBelow and is_string($this->AddExtraRow))
            {
            // suppress extra line after heading..
            $SavedExtra=$this->AddExtraRow;
            $this->setAddRow(false);
            }
            
        // Get first row to find heading text..
        $Row1=reset($RowValues);
        if($LineAbove)
            {
            //ensure we have the right number of cols..
            while(count($Row1)>count($this->Columns))
                { $this->Columns[]=$this->_fixColumn($this->DefaultWidth); }
            $TheOutput.=$this->fitTextLine($LineAbove);
            }
        
        $TheOutput.=$this->fitTextRow(array_keys($Row1)); 
        
        if($LineBelow)
            {
            $TheOutput.=$this->fitTextLine($LineBelow);
            // restore extra line after heading..
            if(is_string($SavedExtra))
                { $this->setAddRow($SavedExtra); }
            }
        
        }
    
    foreach($RowValues as $ThisRow)
        { if(is_array($ThisRow)) $TheOutput.=$this->fitTextRow($ThisRow); }
    
    if($this->EchoIt)
        { echo $TheOutput; }
    else
        { return $TheOutput; }
    
    
    }

public function shortenText($text, $chars=40, $ForceCutoff=false, $UseEllipsis=false)
	{
    /*
        Shortens text if it's longer than $chars, and adds "..." (or "&hellip;" if $UseEllipsis is true)
        Note: with a proportional font, three dots and ellipsis will both use about the same width, 
              but with a fixed font, three dots will be 3 characters wide, and ellipsis only one.
        
        If $ForceCutoff is true, ignores word boundaries and just cuts it
    */
	
    $text=strip_tags($text); // shortening can break html tags
    
    $dots=($UseEllipsis) ? '&hellip;' : '...';
    
	if (strlen($text)>$chars)
		{
        $text = substr($text, 0, $chars);
        
        if(!$ForceCutoff)
            {
            // Find the last position of a space or hyphen (for shortening without breaking words)
            // Maybe we should be using regex \W (non-word character) here?
            $LastSpace=strrpos($text, ' '); if(false===$LastSpace) { $LastSpace=0; }
            $LastDash=strrpos($text, '-');  if(false===$LastDash) { $LastDash=0; }
            
            $EndPos=($LastSpace>$LastDash) ? $LastSpace : $LastDash;
            $text = substr($text, 0, $EndPos);
            }
        
        $text.=$dots;
		}
	return $text;
    }
    
public function shortenFilename($text, $chars=40, $UseEllipsis=false)
	{
	// Contracts a filename, eg "blah blah blah.doc" to "blah bl...doc"
    // The filenamein $text must have a file extension
    // If $UseEllipsis is true, &hellip; is used instead of ...
    
    $dots=($UseEllipsis) ? '&hellip;' : '...';
    
	if (strlen($text)>$chars+3)
		{
        $Ext=end(explode('.', $text));
        $text=substr($text, 0, $chars-strlen($Ext)-3).$dots.$Ext;
		}
	return $text;
    }

public function setDefaultWidth($DefaultWidth)
    {
    if(is_int($DefaultWidth) and $DefaultWidth>0)
        { $this->DefaultWidth=$DefaultWidth; }
    else
        { $this->DefaultWidth=20; }
    }

public function setColumns($Columns)
    {
    if(is_array($Columns))
        { $this->Columns=array_values($Columns); }
        //..array_values ensures numeric indexes starting at zero
    else
        {
        if(is_int($Columns) and $Columns>0)
            { $this->DefaultWidth=$Columns; }
        
        $this->Columns=array($this->DefaultWidth);
        }
        
    // Ensure each column has width and padtype indexes..
    foreach($this->Columns as $Idx=>$ThisCol)
        { $this->Columns[$Idx]=$this->_fixColumn($ThisCol); }

    }

private function _fixColumn($ColDef)
    {
    // Given a user column definition, returns a valid 
    // column definition with all required indexes..
    
    if(is_int($ColDef))
        { $ColDef=array('width'=>$ColDef, 'padtype'=>STR_PAD_RIGHT, 'case'=>'keep'); }
    else
        {
        if(!isset($ColDef['width'])) { $ColDef['width']=$this->DefaultWidth; }
        if(!isset($ColDef['align'])) { $ColDef['align']='left'; }
        if(!isset($ColDef['case'])) { $ColDef['case']='keep'; }
        
        switch(strtoupper(substr($ColDef['align'], 0, 1)))
            {
            case 'C': case '2': // align centre
                $PadType=STR_PAD_BOTH;
                break;
            case 'R': case '0': // align right
                $PadType=STR_PAD_LEFT;
                break;
            case 'L': case '1': default: // align left
                $PadType=STR_PAD_RIGHT;
                break;
            }
        $ColDef['padtype']=$PadType;
        }

    return $ColDef;
    }

public function setRowChar($Separator=null, $LeftChar=null, $RightChar=null)
    {
    // c.f. setLineChar()
    
    $this->RowSep=(is_string($Separator) and strlen($Separator)>0) ? $Separator{0} : '';
    
    $this->RowStart=(is_string($LeftChar) and strlen($LeftChar)>0) ? $LeftChar{0} : '';
    
    if(is_null($RightChar))
        { $this->RowEnd=$this->RowStart; }
    else
        { $this->RowEnd=(is_string($RightChar) and strlen($RightChar)>0) ? $RightChar{0} : ''; }
    
    }

public function setLineChar($Separator=null, $LeftChar=null, $RightChar=null)
    {
    // c.f. setRowChar()
    
    $this->LineSep=(is_string($Separator) and strlen($Separator)>0) ? $Separator{0} : '';
    
    $this->LineStart=(is_string($LeftChar) and strlen($LeftChar)>0) ? $LeftChar{0} : '';
    
    if(is_null($RightChar))
        { $this->LineEnd=$this->LineStart; }
    else
        { $this->LineEnd=(is_string($RightChar) and strlen($RightChar)>0) ? $RightChar{0} : ''; }
    
    }

public function setEOL($EOL)
    {
    if(is_string($EOL))
        { $this->EOL=$EOL; }
    else
        { $this->EOL="\r\n"; }
    }

public function setPadChar($PadChar)
    {
    // Sets the character used to pad each column to make it a fixed width.

    if(is_string($PadChar) and strlen($PadChar)>0)
        { $this->PadChar=$PadChar; }
    else
        { $this->PadChar=' '; }
    }

public function setWrap($WrapText, $MaxLines=0)
    {
    $this->WrapText=(bool)$WrapText;
    $this->MaxLines=(int)$MaxLines;
    }

public function setIndent($Indent)
    {
    if((int)$Indent>=0)
        { $this->Indent=str_repeat(' ', (int)$Indent); }
    
    }

public function setAddRow($AddExtraRow)
    {
    if(is_string($AddExtraRow) and strlen($AddExtraRow)>0)
        { $this->AddExtraRow=$AddExtraRow{0}; }
    else
        { $this->AddExtraRow=(bool)$AddExtraRow; }
    
    }

public function setEchoOn($EchoOn=true)
    {
    //only applies to fitTextRow fitTextLine (not shortenText, shortenFilename)
    $this->EchoIt=(bool)$EchoOn;
    }


} // end class TextFit

?>