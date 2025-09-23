<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class NameConversation extends Conversation
{
    protected $userInfo = [];

    public function run()
    {
        $this->askName();
    }
    public function askName(){
        $this->ask('👋 Xin chào! Tôi là trợ lý mua sắm của bạn. Bạn tên gì?', function(Answer $answer){
            $name = $answer->getText();
            $this->userInfo['name'] = $name;
            $this->say('Rất vui được gặp bạn,' .$name .'! 😊' );
            $this->showProductMenu();
        });
    }
    public function showProductMenu(){
        try{
            $totalProduct = Product::count();
            $totalCategory = Product::distinct('category')->whereNotNull('category')->count();
            $WelcomeMessage = "🛍️ *** Chào mừng {$this->userInfo['name']} đến của hàng! ***\n\n";
            $WelcomeMessage .= "Hiện tại chúng tôi có: \n";
            $WelcomeMessage .= "{$totalProduct} sản phẩm \n";
            $WelcomeMessage .= "{$totalCategory} danh muc \n\n";
        }
        catch(\Exception $e){
             $WelcomeMessage = "🛍️ *** Chào mừng {$this->userInfo['name']} đến của hàng! ***\n\n";
             $WelcomeMessage .= "Xin lỗi, hiện tại không thể truy xuất thông tin sản phẩm.\n";
        }
        $this->say($WelcomeMessage);
        $this->askWhatUserWant();
    }
    public function askWhatUserWant(){
        $question = Question::create('🤔 Bạn muốn sử dụng phương thức nào')
        ->fallback('Vui lòng chọn một tùy chọn')->callbackId('user_choice');
        $question->addButtons([
            Button::create('🔍 Tìm sản phẩm')->value('search_product'),
            Button::create('📊 Thống kê')->value('view_status'),
            Button::create('🎯 Sản phẩm khuyến mãi')->value('view_deals'),
        ]);
        $this->ask($question, function(Answer $answer){
            $choice = $answer->getValue() ?? $answer->getText();
            switch($choice){
                case 'search_product':
                    $this->handleSearchProduct();
                    break;
                case 'view_status':
                    $this->handleViewStatus();
                    break;
                case 'view_deals':
                    $this->handleViewDeals();
                    break;
                default:
                     $this->handleDefaultChoice($answer->getText());
                     break;
            }
        });
    }
    protected function handleSearchProduct(){
        $this->ask('🔍 Nhập tên sản phẩm bạn muốn tìm', function(Answer $answer){
            $search = ucfirst(strtolower(trim($answer->getText())));
            $this->searchProduct($search);
        });
    }
    protected function handleViewStatus(){
        try{
            $totalProduct = Product::count();
            $totalCategory = Product::distinct('category')->whereNotNull('category')->count();
            $productWithDiscount = Product::whereNotNull('discount_price')->count();
            $out0stock = Product::where('quantity', '0')->orWhereNull('quantity')->count();
            $responses  = "📊 ***Thống kê cửa hàng:*** <br>";
            $responses .= "🛍️ Tổng sản phẩm: **{$totalProduct}**<br>";
            $responses .= "📂 Tổng danh mục: **{$totalCategory}**<br>";
            $responses .= "🎯 Sản phẩm khuyến mãi: **{$productWithDiscount}**<br>";
            $responses .= "❌ Hết hàng: **{$out0stock}**<br>";
            $responses .= "✅ Còn hàng: **" . ($totalProduct - $out0stock) ."**<br>";
            
        }
        catch(\Exception $e){
            $this->say("❌ Có lỗi xảy ra: " . $e->getMessage());
        }
        $this->say($responses);
        $this->showContinueOptions();
    }
    protected function handleViewDeals(){
        try{
            $dealsProduct = Product::whereNotNull('discount_price')
            ->where('discount_price', '<', DB::raw('price'))->get();
            if($dealsProduct->count() > 0){
                $responses = "🎯 **Sản phẩm đang khuyến mãi:**<br>";
            }
            foreach($dealsProduct as $item){
                $discount = (($item->price - $item->discount_price)/ $item->price) * 100;
                $responses .= "🔥 **{$item->title}**<br>";
                $responses .= "💰 ~~$" . number_format($item->price). "USD ~~ → **" . number_format($item->discount_price) ."USD **<br>";
                $responses .= "📉 Giảm  " . round($discount) . "%<br>";
            }
        }
        catch(\Exception $e){
            $this->say("❌ Có lỗi xảy ra: " . $e->getMessage());
        }
        $this->say($responses);
        $this->showContinueOptions();
    }
    protected function handleDefaultChoice($userInput){
        $this->say("Tôi hiểu bạn muốn : '$userInput'<br>" .
        "💡 Bạn có thể hỏi:<br>" .
        "• 'san pham [danh mục]' - xem sản phẩm<br>" .
        "• 'tim [tên sản phẩm]' - tìm sản phẩm <br>" .
        "• 'tong san pham' - thống kê"); 
        $this->showContinueOptions();
    }
    protected function searchProduct($searchTerm){
        try{
            $product = Product::where('title', 'LIKE', "%{$searchTerm}%")->orWhere('category', 'LIKE', "%{$searchTerm}%")->get();
            if($product->count()> 0){
                $responses = "🔍 *** Tìm thấy sản phẩm cho '{$searchTerm}': *** <br>";
                foreach($product as $item){
                    $responses .= "📦*** {$item->title} ***<br>";
                    $responses .= "🏷️ Danh mục: {$item->category}\n";
                    $responses .= "💰 Giá: $" . number_format($item->price,2) ." USD";
                    if(!empty($item->discount_price)){
                        $responses .= " -> ***". number_format($item->discount_price,2) ." USD ***";
                    }
                    $responses .= "\n📊 Số lượng: {$item->quantity}<br>";
                }
            }
            else{
                $responses = "😢 Không tìm thấy sản phẩm nào cho '{$searchTerm}'";
            }
            $this->say($responses);
        }       
        catch(\Exception $e){
            $this->say("❌ Có lỗi xảy ra:" .$e->getMessage());
        }
        $this->showContinueOptions();
    }
    protected function showContinueOptions(){
        $this->ask('❓ Bạn có muốn làm gì khác không? (gõ "menu" để xem lại menu, "bye" để kết thúc)', function(Answer $answer){
            $responses = strtolower(trim($answer->getText()));
            if(in_array($responses, ['back', 'menu', 'quay lại'])){
                $this->askWhatUserWant();
            }
            else if(in_array($responses, ['bye', 'exit', 'quit','tạm biệt'])){
                $this->say("👋 Tạm biệt {$this->userInfo['name']}! Hẹn gặp lại bạn sớm! 😊");          
            }
        });
    }
}