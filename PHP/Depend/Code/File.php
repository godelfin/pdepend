<?php
/**
 * This file is part of PHP_Depend.
 * 
 * PHP Version 5
 *
 * Copyright (c) 2008, Manuel Pichler <mapi@pdepend.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   QualityAssurance
 * @package    PHP_Depend
 * @subpackage Code
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id$
 * @link       http://www.manuel-pichler.de/
 */

require_once 'PHP/Depend/Code/NodeI.php';
require_once 'PHP/Depend/Util/UUID.php';

/**
 * This class provides an interface to a single source file.
 *
 * @category   QualityAssurance
 * @package    PHP_Depend
 * @subpackage Code
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.manuel-pichler.de/
 */
class PHP_Depend_Code_File implements PHP_Depend_Code_NodeI
{
    /**
     * The unique identifier for this function.
     *
     * @type PHP_Depend_Util_UUID
     * @var PHP_Depend_Util_UUID $uuid
     */
    protected $uuid = null;
    
    /**
     * The source file name/path.
     *
     * @type string
     * @var string $fileName
     */
    protected $fileName = null;
    
    /**
     * Normalized code in this file.
     *
     * @type string
     * @var string $source
     */
    protected $source = null;
    
    /**
     * The lines of code in this file.
     *
     * @type array<string>
     * @var array(integer=>string) $loc
     */
    protected $loc = null;
    
    /**
     * The comment for this type.
     *
     * @type string
     * @var string $docComment
     */
    protected $docComment = null;
    
    /**
     * Constructs a new source file instance.
     *
     * @param string $fileName The source file name/path.
     */
    public function __construct($fileName)
    {
        if ($fileName !== null) {
            $this->fileName = realpath($fileName);
        }
        
        $this->uuid = new PHP_Depend_Util_UUID();
    }
    
    /**
     * Returns the physical file name for this object.
     *
     * @return string
     */
    public function getName()
    {
        return $this->fileName;
    }
    
    /**
     * Returns the physical file name for this object.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    
    /**
     * Returns a uuid for this code node.
     *
     * @return string
     */
    public function getUUID()
    {
        return (string) $this->uuid;
    }
    
    /**
     * Returns the lines of code with stripped whitespaces.
     *
     * @return array(integer=>string)
     */
    public function getLoc()
    {
        $this->readSource();
        return $this->loc;
    }
    
    /**
     * Returns normalized source code with stripped whitespaces.
     *
     * @return array(integer=>string)
     */
    public function getSource()
    {
        $this->readSource();
        return $this->source;
    }
    
    /**
     * Returns the doc comment for this item or <b>null</b>.
     *
     * @return string
     */
    public function getDocComment()
    {
        return $this->docComment;
    }
    
    /**
     * Sets the doc comment for this item.
     *
     * @param string $docComment The doc comment block.
     * 
     * @return void
     */
    public function setDocComment($docComment)
    {
        $this->docComment = $docComment;
    }
    
    /**
     * Visitor method for node tree traversal.
     *
     * @param PHP_Depend_Code_NodeVisitorI $visitor The context visitor 
     *                                              implementation.
     * 
     * @return void
     */
    public function accept(PHP_Depend_Code_NodeVisitorI $visitor)
    {
        $visitor->visitFile($this);
    }
    
    /**
     * Returns the string representation of this class.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->fileName;
    }
    
    /**
     * Reads the source file if required.
     *
     * @return void
     */
    protected function readSource()
    {
        if ($this->loc === null) {
            $source = file_get_contents($this->fileName);
            
            $this->loc = preg_split('#(\r\n|\n|\r)#', $source);
            
            $this->source = implode("\n", $this->loc);
        }
    }
}