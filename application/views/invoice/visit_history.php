<?php
/*
 * The MIT License
 *
 * Copyright 2019 Dilshan  Jayasnka.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
?>
<table class="table table-bordered table-hover">
    <tr>
        <th>Visit Date</th>
        <th>Username</th>
        <th>Status</th>
        <th>Location</th>
    </tr>
    <?php
    foreach ($history as $row) {
        ?>
        <tr>
            <td><?php echo $row->visited_date ?></td>
            <td><?php echo $row->username ?></td>
            <td><?php echo $row->status=="1"?"Collected":"Visit Only" ?></td>
            <td><a href="http://maps.google.com/?q=<?php echo $row->lat ?>,<?php echo $row->long ?>">Open Location</a></td>
        </tr>
        <?php
    }
    ?>

</table>

