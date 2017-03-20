<?php
class Packing{
    public $boxList = array();
    public $itemList = array();
    public $ToWeight = null; // total weight of all item in list
    public $BoxToMaxWeight = null; // total weight of all box in list
    public $boxIsSorted = false;
    public $itemIsSorted = false;  
    // error and result handle , temp may change ? 
    public $error = null;
    public $lastResult = null;

    public function getBoxlist() {
        return $this->boxList;
    }

    public function getItemlist() {
        return $this->itemList;
    }

    public function addBox($name,$weightLimit,$boxHeight,$boxLength,$boxWidth){
        $this->boxList[] = array("boxname"=>$name , "maxweight"=>$weightLimit,"boxheight"=>$boxHeight,"boxlength"=>$boxLength,"boxwidth"=>$boxWidth,"boxvolume"=>$this->getBoxVolume($boxHeight,$boxLength,$boxWidth));
        $this->boxIsSorted = false;
        //$this->sortBoxWeight();
        $this->sortBoxVolume();
    }

    public function addItem($name,$weight,$itemHeight,$itemLength,$itemWidth,$qty){
        $this->itemList[] = array("itemname"=>$name,"itemweight"=>$weight,"itemheight"=>$itemHeight,"itemlength"=>$itemLength,"itemwidth"=>$itemWidth,"itemquantity"=>$qty,"itemvolume"=>$this->getItemVolume($itemHeight,$itemLength,$itemWidth));
        $this->itemIsSorted = false;
        //$this->sortItemWeight();
        $this->sortItemVolume();
    }

    public function getToWeight(){  // Toweight of all items
        $this->ToWeight = 0;
        foreach($this->itemList as $val){
            $this->ToWeight = $this->ToWeight + ($val['itemweight'] * $val['itemquantity']);
        }
        return $this->ToWeight;
    }

    public function getToBoxMaxWeight(){
        $this->BoxToMaxWeight = 0;
        foreach($this->boxList as $val){
            $this->BoxToMaxWeight = $this->BoxToMaxWeight + $val['maxweight'];
        }
        return $this->BoxToMaxWeight;
    }

    public function sortBoxWeight(){
        usort($this->boxList, function ($boxA, $boxB) {
            return $boxA['maxweight'] < $boxB['maxweight'];
        });
        $this->boxIsSorted = true;
        //var_dump($temp);
    }

    public function sortItemWeight(){
        usort($this->itemList, function ($itemA, $itemB) {
            return (($itemA['itemweight']*$itemA['itemquantity']) < ($itemB['itemweight']*$itemB['itemquantity']));
        });
        $this->itemIsSorted = true;
        //var_dump($temp);
    }

    public function sortBoxVolume(){
        usort($this->boxList, function ($boxA, $boxB) {
            return $boxA['boxvolume'] < $boxB['boxvolume'];
        });
        $this->boxIsSorted = true;
    }

    public function sortItemVolume(){
        usort($this->itemList, function ($itemA, $itemB) {
            return ($itemA['itemvolume'] < $itemB['itemvolume']);
        });
        $this->itemIsSorted = true;
        //var_dump($temp);
    }

    public function getItemVolume($itemHeight,$itemLength,$itemWidth){
        $itemVol = $itemHeight * $itemLength * $itemWidth;
        return $itemVol;
    }

    public function getBoxVolume($boxHeight,$boxLength,$boxWidth){
        $boxVol = $boxHeight * $boxLength * $boxWidth;
        return $boxVol;
    }

    public function insertItem2Box($boxlist,$itemlist){
        // unfinished
        $boxcount = count($boxlist);
        $itemcount = count($itemlist);
        if($boxcount>0 && $itemcount>0){
            //check total weight heavy exception
            if($this->getToBoxMaxWeight() < $this->getToWeight()){
                $this->error = "All item is too heavy";
                return $this->error;
                }
            // check too large item exception
            foreach($itemlist as $itemval){
                $itemH = $itemval['itemheight'];
                $itemL = $itemval['itemlength'];
                $itemW = $itemval['itemwidth'];
                $itemMw = $itemval['itemweight'];
                $boxWCmpCount = 0;
                $boxSCmpCount = 0;
                foreach($boxlist as $boxval){
                    $boxH = $boxval['boxheight'];
                    $boxL = $boxval['boxlength'];
                    $boxW = $boxval['boxwidth'];
                    $boxMw = $boxval['maxweight'];
                    // check too heavy item exception
                    if($itemMw>$boxMw){
                        $boxWCmpCount += 1;
                        if($boxWCmpCount == $boxcount){
                            $this->error = "some item is too heavy";
                            return $this->error;
                        }
                    }
                    if( $itemH > $boxH || $itemH > $boxL || $itemH > $boxW || $itemL > $boxH || $itemL > $boxL || $itemL > $boxW || $itemW > $boxH || $itemW > $boxL || $itemW > $boxW){//stupid bruteforce check using nested later??
                        $boxSCmpCount += 1;
                        if($boxSCmpCount == $boxcount){
                            $this->error = "some item can't fit in all boxes";
                            return $this->error;
                        }
                    }
                }
            }
            return "success";
        }else{
            $this->error = "The item or box is lower than 1";
            return $this->error;
        }
    }
}
?>

Test <br>

<?php
    //Usage test
    $package = new Packing();
    $package->addBox("GreenBox",246,50,50,50);
    $package->addBox("RedBox",30,50,50,50);
    //$package->addBox("YellowBox",60,50,50,50);
    //$package->addBox("BlueBox",50,50,50,50);
    //$package->addBox("GreyBox",40,50,50,50);
    print_r($package->getBoxlist());
    echo "<br>";

    $package->addItem("item1",21,10,20,30,2);
    $package->addItem("item2",5,5,10,20,4);
    $package->addItem("item3",1,3,4,7,5);
    $package->addItem("item4",100,1,5,10,2);
    print_r($package->getItemlist());
    echo "<br>";

    echo $package->getToBoxMaxWeight();
    echo "<br>";
    echo $package->getToWeight();    
    
    // try sorting
    echo "<br>";
    //$temp = $package->getBoxlist();
    //usort($temp, function ($a, $b) {
    //    return $a['maxweight'] < $b['maxweight'];
    //});
    //var_dump($temp);
    echo $package->insertItem2Box($package->getBoxlist(),$package->getItemlist());
    echo "<br>";
    $package->addItem("item5",1,100,5,10,2);
    echo $package->insertItem2Box($package->getBoxlist(),$package->getItemlist());
?>