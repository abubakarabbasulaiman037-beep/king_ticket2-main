<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            // COMMERCIAL BANKS (TRADITIONAL)
            ['name' => 'Access Bank', 'code' => '044', 'category' => 'commercial'],
            ['name' => 'Zenith Bank', 'code' => '057', 'category' => 'commercial'],
            ['name' => 'Guaranty Trust Bank (GTBank)', 'code' => '058', 'category' => 'commercial'],
            ['name' => 'United Bank for Africa (UBA)', 'code' => '033', 'category' => 'commercial'],
            ['name' => 'First Bank of Nigeria', 'code' => '011', 'category' => 'commercial'],
            ['name' => 'Fidelity Bank', 'code' => '070', 'category' => 'commercial'],
            ['name' => 'Union Bank', 'code' => '032', 'category' => 'commercial'],
            ['name' => 'Stanbic IBTC', 'code' => '221', 'category' => 'commercial'],
            ['name' => 'Ecobank Nigeria', 'code' => '050', 'category' => 'commercial'],
            ['name' => 'FCMB', 'code' => '214', 'category' => 'commercial'],
            ['name' => 'Sterling Bank', 'code' => '232', 'category' => 'commercial'],
            ['name' => 'Polaris Bank', 'code' => '076', 'category' => 'commercial'],
            ['name' => 'Unity Bank', 'code' => '215', 'category' => 'commercial'],
            ['name' => 'Wema Bank', 'code' => '035', 'category' => 'commercial'],
            ['name' => 'Keystone Bank', 'code' => '082', 'category' => 'commercial'],
            ['name' => 'Providus Bank', 'code' => '101', 'category' => 'commercial'],
            ['name' => 'Jaiz Bank', 'code' => '301', 'category' => 'commercial'],
            ['name' => 'Taj Bank', 'code' => '302', 'category' => 'commercial'],
            ['name' => 'Titan Trust Bank', 'code' => '102', 'category' => 'commercial'],
            ['name' => 'Parallex Bank', 'code' => '104', 'category' => 'commercial'],
            ['name' => 'PremiumTrust Bank', 'code' => '105', 'category' => 'commercial'],
            ['name' => 'Globus Bank', 'code' => '103', 'category' => 'commercial'],
            ['name' => 'Lotus Bank', 'code' => '106', 'category' => 'commercial'],
            ['name' => 'Optimus Bank', 'code' => '107', 'category' => 'commercial'],
            ['name' => 'Signature Bank', 'code' => '108', 'category' => 'commercial'],
            ['name' => 'Standard Chartered Bank', 'code' => '068', 'category' => 'commercial'],
            ['name' => 'Citibank Nigeria', 'code' => '023', 'category' => 'commercial'],
            ['name' => 'SunTrust Bank', 'code' => '100', 'category' => 'commercial'],
            ['name' => 'Coronation Merchant Bank', 'code' => '559', 'category' => 'commercial'],
            ['name' => 'FBNQuest Merchant Bank', 'code' => '560', 'category' => 'merchant'],
            ['name' => 'Nova Merchant Bank', 'code' => '561', 'category' => 'merchant'],
            ['name' => 'Rand Merchant Bank', 'code' => '562', 'category' => 'merchant'],
            ['name' => 'Greenwich Merchant Bank', 'code' => '563', 'category' => 'merchant'],

            // DIGITAL BANKS / FINTECH BANKS / WALLET BANKS
            ['name' => 'Opay', 'code' => '110', 'category' => 'digital'],
            ['name' => 'PalmPay', 'code' => '999997', 'category' => 'digital'],
            ['name' => 'Moniepoint', 'code' => '999996', 'category' => 'digital'],
            ['name' => 'Kuda Bank', 'code' => '999995', 'category' => 'digital'],
            ['name' => 'Carbon', 'code' => '999994', 'category' => 'digital'],
            ['name' => 'FairMoney', 'code' => '999993', 'category' => 'digital'],
            ['name' => 'Sparkle', 'code' => '999992', 'category' => 'digital'],
            ['name' => 'VFD Bank', 'code' => '566', 'category' => 'digital'],
            ['name' => 'Eyowo', 'code' => '999991', 'category' => 'digital'],
            ['name' => 'Paga', 'code' => '999990', 'category' => 'digital'],
            ['name' => 'PiggyVest', 'code' => '999989', 'category' => 'digital'],
            ['name' => 'Cowrywise', 'code' => '999988', 'category' => 'digital'],
            ['name' => 'ALAT by Wema', 'code' => '997', 'category' => 'digital'],
            ['name' => 'Mintyn Bank', 'code' => '999987', 'category' => 'digital'],
            ['name' => 'Rubies Bank', 'code' => '999986', 'category' => 'digital'],
            ['name' => 'Branch', 'code' => '999985', 'category' => 'digital'],
            ['name' => 'PalmCredit', 'code' => '999984', 'category' => 'digital'],
            ['name' => 'Chipper Cash', 'code' => '999983', 'category' => 'digital'],
            ['name' => 'Eversend', 'code' => '999982', 'category' => 'digital'],
            ['name' => 'OurPass', 'code' => '999981', 'category' => 'digital'],
            ['name' => 'Fundall', 'code' => '999980', 'category' => 'digital'],
            ['name' => 'Mint', 'code' => '999979', 'category' => 'digital'],
            ['name' => 'Mkobo', 'code' => '999978', 'category' => 'digital'],
            ['name' => 'Quickteller', 'code' => '999977', 'category' => 'digital'],
            ['name' => 'Remita', 'code' => '999976', 'category' => 'digital'],
            ['name' => 'Flutterwave', 'code' => '998', 'category' => 'digital'],
            ['name' => 'Paystack', 'code' => '999975', 'category' => 'digital'],
            ['name' => 'Interswitch', 'code' => '999974', 'category' => 'digital'],

            // PAYMENT SERVICE BANKS (PSB)
            ['name' => 'SmartCash PSB', 'code' => '309', 'category' => 'psb'],
            ['name' => 'MoMo PSB', 'code' => '310', 'category' => 'psb'],
            ['name' => '9PSB', 'code' => '311', 'category' => 'psb'],
            ['name' => 'Hope PSB', 'code' => '312', 'category' => 'psb'],
            ['name' => 'MTN MoMo', 'code' => '313', 'category' => 'psb'],
            ['name' => 'Airtel SmartCash', 'code' => '314', 'category' => 'psb'],

            // MICROFINANCE BANKS (POPULAR)
            ['name' => 'LAPO Microfinance Bank', 'code' => '576', 'category' => 'microfinance'],
            ['name' => 'NIRSAL Microfinance Bank', 'code' => '577', 'category' => 'microfinance'],
            ['name' => 'Accion Microfinance Bank', 'code' => '578', 'category' => 'microfinance'],
            ['name' => 'AB Microfinance Bank', 'code' => '579', 'category' => 'microfinance'],
            ['name' => 'Boctrust Microfinance Bank', 'code' => '580', 'category' => 'microfinance'],
            ['name' => 'Hasal Microfinance Bank', 'code' => '581', 'category' => 'microfinance'],
            ['name' => 'Mainstreet Microfinance Bank', 'code' => '582', 'category' => 'microfinance'],
            ['name' => 'Infinity Microfinance Bank', 'code' => '583', 'category' => 'microfinance'],
            ['name' => 'Mutual Trust Microfinance Bank', 'code' => '584', 'category' => 'microfinance'],
            ['name' => 'Fina Trust Microfinance Bank', 'code' => '585', 'category' => 'microfinance'],
            ['name' => 'Addosser Microfinance Bank', 'code' => '586', 'category' => 'microfinance'],
            ['name' => 'MIC Microfinance Bank', 'code' => '587', 'category' => 'microfinance'],
            ['name' => 'Assets Microfinance Bank', 'code' => '588', 'category' => 'microfinance'],
            ['name' => 'Baobab Microfinance Bank', 'code' => '589', 'category' => 'microfinance'],
            ['name' => 'Covenant Microfinance Bank', 'code' => '590', 'category' => 'microfinance'],
            ['name' => 'Grooming Microfinance Bank', 'code' => '591', 'category' => 'microfinance'],
            ['name' => 'Empire Trust Microfinance Bank', 'code' => '592', 'category' => 'microfinance'],
            ['name' => 'Rephidim Microfinance Bank', 'code' => '593', 'category' => 'microfinance'],
            ['name' => 'Seedvest Microfinance Bank', 'code' => '594', 'category' => 'microfinance'],
            ['name' => 'Fortis Microfinance Bank', 'code' => '595', 'category' => 'microfinance'],
            ['name' => 'Peace Microfinance Bank', 'code' => '596', 'category' => 'microfinance'],
            ['name' => 'Advans La Fayette MFB', 'code' => '597', 'category' => 'microfinance'],
            ['name' => 'NPF Microfinance Bank', 'code' => '598', 'category' => 'microfinance'],
            ['name' => 'Uhuru Microfinance Bank', 'code' => '599', 'category' => 'microfinance'],
            ['name' => 'Ibile Microfinance Bank', 'code' => '600', 'category' => 'microfinance'],
            ['name' => 'Letshego Microfinance Bank', 'code' => '601', 'category' => 'microfinance'],

            // TELCO WALLETS
            ['name' => 'Glo Wallet', 'code' => '315', 'category' => 'telco'],
            ['name' => '9mobile Wallet', 'code' => '316', 'category' => 'telco'],
        ];

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($banks, 50) as $chunk) {
            DB::table('banks')->insert($chunk);
        }
    }
}
