<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../public_html/common/utils.php';

// Run tests: ./vendor/bin/phpunit tests

final class UtilsTest extends TestCase {

  public function testTest(): void {
      $this->assertSame('a', 'a');
  }

  public function test_str_clean_null(): void {
      $this->assertSame(null, clean_str(null));
  }

  public function test_str_clean_eol(): void {
      $this->assertSame("A\nB", clean_str("A\r\nB"));
      $this->assertSame("A\nB", clean_str("A\rB"));
      $this->assertSame("A\nB", clean_str("A\nB"));
  }

  public function test_str_clean_dash(): void {
      $this->assertSame("-", clean_str("—"));
  }

}
