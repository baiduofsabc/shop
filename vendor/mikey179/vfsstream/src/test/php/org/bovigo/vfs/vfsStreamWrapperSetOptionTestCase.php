<?php
/**
 */
namespace org\bovigo\vfs;
/**
 */
class vfsStreamWrapperSetOptionTestCase extends \BC_PHPUnit_Framework_TestCase
{
    /**
     */
    protected $root;

    /**
     */
    public function setUp()
    {
        $this->root = vfsStream::setup();
        vfsStream::newFile('foo.txt')->at($this->root);
    }

    /**
     */
    public function setBlockingDoesNotWork()
    {
        $fp = fopen(vfsStream::url('root/foo.txt'), 'rb');
        $this->assertFalse(stream_set_blocking($fp, 1));
        fclose($fp);
    }

    /**
     */
    public function removeBlockingDoesNotWork()
    {
        $fp = fopen(vfsStream::url('root/foo.txt'), 'rb');
        $this->assertFalse(stream_set_blocking($fp, 0));
        fclose($fp);
    }

    /**
     * @test
     */
    public function setTimeoutDoesNotWork()
    {
        $fp = fopen(vfsStream::url('root/foo.txt'), 'rb');
        $this->assertFalse(stream_set_timeout($fp, 1));
        fclose($fp);
    }

    /**
     * @test
     */
    public function setWriteBufferDoesNotWork()
    {
        $fp = fopen(vfsStream::url('root/foo.txt'), 'rb');
        $this->assertEquals(-1, stream_set_write_buffer($fp, 512));
        fclose($fp);
    }
}
