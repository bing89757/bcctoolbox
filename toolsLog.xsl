<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
  <center>
  <body>
  <h2>Tools Logged</h2>
  <table border="1">
    <tr bgcolor="lightgreen">
      <th style="text-align:left">Tool</th>
      <th style="text-align:left">User</th>
      <th style="text-align:left">Check Out</th>
      <th style="text-align:left">Date Out</th>
      <th style="text-align:left">Time Out</th>
      <th style="text-align:left">Check In</th>
      <th style="text-align:left">Date In</th>
      <th style="text-align:left">Time In</th>
    </tr>
    <xsl:for-each select="csv_data/row">
    <xsl:sort select="Tool"/>
    <tr>
      <td><xsl:value-of select="Tool" /></td>
      <td><xsl:value-of select="User" /></td>
      <td><xsl:value-of select="Action" /></td>
      <td><xsl:value-of select="Date" /></td>
      <td><xsl:value-of select="Time" /></td>
      <td><xsl:value-of select="CheckIn" /></td>
      <td><xsl:value-of select="DateIn" /></td>
      <td><xsl:value-of select="TimeIn" /></td>
    </tr>
    </xsl:for-each>
  </table>
  </body>
  </center>
  </html>
</xsl:template>
</xsl:stylesheet>
