
<?php
    $subtotal = isset($_POST['subtotal']) ? intval($_POST['subtotal']) : '';
    $tipPercentage = isset($_POST['tipPercentage']) ? $_POST['tipPercentage'] : 'tipOption1';
    $split = isset($_POST['split']) ? intval($_POST['split']) : 1;
    $tipError = false;
    $splitError = false;
    $tipOptions = [
        'tipOption1' => 0.10,
        'tipOption2' => 0.15,
        'tipOption3' => 0.20,
        'tipOption4' => isset($_POST['customTip']) && $_POST['customTip'] !== '' && $tipPercentage === 'tipOption4' ? floatval($_POST['customTip']) : ''
    ];
    if((!is_integer($subtotal) && $subtotal !== '') || $subtotal < 0) {
        $subtotal = 0;
    }
    if((!is_float($tipOptions['tipOption4']) && $tipOptions['tipOption4'] !== '') || ($tipPercentage === 'tipOption4' && $tipOptions['tipOption4'] === '') || intval($tipOptions['tipOption4']) < 0) {
        $tipError = true;
    }
    if(!is_integer($split) || $split <= 0) {
        $splitError = true;
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Raymond's Tip Calculator</title>
</head>
<body>
    <div class="row">
        <div class="container">
            <div class="col-md-offset-3 col-md-6">
                <div class="jumbotron">
                    <h1>Tip calculator</h1>
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
                        <div class="form-group <?php echo $subtotal === 0 ? 'has-warning' : '' ?>">
                            <label class="col-sm-2 control-label" for="subtotal">Bill Subtotal</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="subtotal" id="subtotal" placeholder="0" value="<?php echo $subtotal ?>"/>
                            </div>
                        </div>
                        <div class="form-group <?php echo $tipError ? 'has-warning' : '' ?>">
                            <label class="col-sm-2 control-label" for="subtotal">Tip percentage</label>
                            <div class="col-sm-10">
                                <?php foreach($tipOptions as $tipOptionKey => $val) { ?>
                                    <div class="radio-inline">
                                        <?php if($tipOptionKey != 'tipOption4') {?>
                                        <label>
                                            <input type="radio" name="tipPercentage" value="<?php echo $tipOptionKey; ?>" <?php echo $tipPercentage === $tipOptionKey ? 'checked' : '' ?> />
                                            <?php echo $val * 100; ?>%
                                        </label>
                                        <?php } else { ?>
                                            <label>
                                                <input type="radio" name="tipPercentage" value="<?php echo $tipOptionKey; ?>" <?php echo $tipPercentage === $tipOptionKey ? 'checked' : '' ?> />
                                                Custom:
                                            </label>
                                            <input type="text" name="customTip" value="<?php echo $val; ?>" />
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group <?php echo $splitError ? 'has-warning' : '' ?>">
                            <label class="col-sm-2 control-label" for="split">Split</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="split" id="split" placeholder="0" value="<?php echo $split ?>"/>
                            </div>
                            <div class="col-sm-6">
                            <label class="control-label" for="split">person(s)</label>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <input type="submit" class="btn btn-primary" value="Submit"></input>
                        </div>
                    </form>
                    <?php if($subtotal && $tipPercentage && (!$tipError && !$splitError)) { ?>
                    <?php
                        if($tipPercentage === 'tipOption4') {
                            $tipPercent = $tipOptions[$tipPercentage] / 100;
                        } else {
                            $tipPercent = $tipOptions[$tipPercentage];
                        }
                        $tip = number_format($subtotal * $tipPercent, 2);
                    ?>
                    <?php $total = number_format($subtotal + $tip, 2); ?>
                        <div class="alert alert-info">
                            <p>Tip: $<?php echo $tip; ?></p>
                            <p>Total: $<?php echo $total; ?></p>
                            <?php if($split > 1) { ?>
                                <?php $splitTips = number_format($tip / $split, 2); ?>
                                <?php $splitTotal = number_format($total / $split, 2); ?>
                                <p>Tip each: $<?php echo $splitTips; ?></p>
                                <p>Total each: $<?php echo $splitTotal; ?></p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"/>
</body>
</html>
