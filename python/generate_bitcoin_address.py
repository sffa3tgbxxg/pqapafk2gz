from bip_utils import Bip39MnemonicGenerator, Bip39SeedGenerator, Bip44, Bip44Coins
mnemonic = Bip39MnemonicGenerator().FromWordsNumber(12)
seed = Bip39SeedGenerator(mnemonic).Generate()
bip44_mst = Bip44.FromSeed(seed, Bip44Coins.BITCOIN)
address = bip44_mst.PublicKey().ToAddress()
print(f"{mnemonic}\n{address}")