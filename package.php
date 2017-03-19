<?php
class Packing{

    public $boxList = array();
    public $itemList = array();
    public $ToWeight = null; // total weight of all item in list
    public $BoxToMaxWeight = null; // total weight of all box in list
    public $boxIsSorted = false;
    public $itemIsSorted = false;
    public $error = null;

    // temp may change ?
    public $lastResult = null;

    public function getBoxlist() {
        return $this->boxList;
    }

    public function getItemlist() {
        return $this->itemList;
    }

    public function addBox($name,$weightLimit){
        $this->boxList[] = array("boxname"=>$name , "maxweight"=>$weightLimit);
        $this->boxIsSorted = false;
        $this->sortBoxWeight();
    }

    public function addItem($name,$weight,$qty){
        $this->itemList[] = array("itemname"=>$name,"itemweight"=>$weight,"itemquantity"=>$qty);
        $this->itemIsSorted = false;
        $this->sortItemWeight();
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
}
?>

Test <br>

<?php
    //Usage test
    $package = new Packing();
    $package->addBox("GreenBox",40);
    //$package->addBox("RedBox",30);
    //$package->addBox("YellowBox",60);
    //$package->addBox("BlueBox",50);
    //$package->addBox("GreyBox",40);
    print_r($package->getBoxlist());
    echo "<br>";

    $package->addItem("item1",10,2);
    $package->addItem("item2",5,4);
    $package->addItem("item3",1,5);
    $package->addItem("item4",100,2);
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
    echo $package->placeItemSingleBox();
?>