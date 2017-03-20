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
        return $packedBox;
    }


    public function placeItemSingleBox(){
        //Place all item in list into singlebox
        // Check and sort item and box
        if(!$this->itemIsSorted){
            $this->sortItemWeight();
            $this->itemIsSorted = true;
        }
        if(!$this->boxIsSorted){
            $this->sortItemWeight();
            $this->boxIsSorted = true;
        }
        if(count($this->boxList)==1){
            $this->getToWeight();
            $this->getToBoxMaxWeight();
            if($this->BoxToMaxWeight > 0){
                if($this->BoxToMaxWeight < $this->ToWeight){
                    $this->lastResult = "The item total weight is higher box max weight";
                    return $this->lastResult;
                }else{
                    $this->lastResult = "The item is fit in box by weight";
                    return $this->lastResult;
                }
            }else{
                $this->error = "Box weight isn't positive number";
                return $this->error;
            }
        }else{
            $this->error = "Box number isn't 1";
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
    //$package->addBox("RedBox",30,50,50,50);
    //$package->addBox("YellowBox",60,50,50,50);
    //$package->addBox("BlueBox",50,50,50,50);
    //$package->addBox("GreyBox",40,50,50,50);
    print_r($package->getBoxlist());
    echo "<br>";

    $package->addItem("item1",10,10,20,30,2);
    $package->addItem("item2",5,5,10,20,4);
    $package->addItem("item3",1,3,4,7,5);
    $package->addItem("item4",100,1,5,10,2);
    print_r($package->getItemlist());
    echo "<br>";

    echo $package->getToWeight();
    
    // try sorting
    echo "<br>";
    //$temp = $package->getBoxlist();
    //usort($temp, function ($a, $b) {
    //    return $a['maxweight'] < $b['maxweight'];
    //});
    //var_dump($temp);
    //echo $package->placeItemSingleBox();
?>